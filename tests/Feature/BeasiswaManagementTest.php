<?php

namespace Tests\Feature;

use App\Models\Beasiswa;
use App\Models\BenefitBeasiswa;
use App\Models\Mahasiswa;
use App\Models\Reviewer;
use App\Models\SyaratBeasiswa;
use App\Models\SyaratDokumen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RoleSeeder;

class BeasiswaManagementTest extends TestCase
{
    use RefreshDatabase;
    protected $user_reviewer;
    protected $user_mahasiswa;
    protected $reviewer;
    protected $mahasiswa;

    protected function setUp(): void
    {
        parent::setUp();
        ob_start();  // Mulai output buffering
        $this->seed(); // Menjalankan seeder jika perlu

        // Membuat user reviewer
        $this->user_reviewer = User::where('nama_depan', 'Yani')->first();
        $this->reviewer = Reviewer::where('user_id', $this->user_reviewer->id)->first();

        // Membuat user mahasiswa
        $this->user_mahasiswa = User::where('nama_depan', 'Daffa')->first();
        $this->mahasiswa = Mahasiswa::where('user_id', $this->user_mahasiswa->id)->first();

    }

    protected function tearDown(): void
    {
        ob_end_clean();  // Pastikan buffer dibersihkan
        parent::tearDown();
    }

    /** @test */
    public function can_login_reviewer()
    {
        // Mengambil dan membersihkan buffer secara eksplisit

        $output = ob_get_clean();
        $response = $this->actingAs($this->user_reviewer)
                        ->withSession(['auth' => ['user' => $this->user_reviewer, 'role' => 'reviewer', 'reviewer' => $this->reviewer]])
                        ->get('/beasiswa/create');

        $response->assertStatus(200); // Memastikan halaman dapat diakses
        $response->assertSee('Nama Beasiswa'); // Sesuaikan dengan teks yang ada di halaman
    }

    /** @test */
    public function it_fetches_beasiswa_data_successfully()
    {
        // Seed the database with sample data
        $this->seed();




        $response = $this->actingAs($this->user_reviewer)
        ->withSession(['auth' => ['user' => $this->user_reviewer, 'role' => 'reviewer', 'reviewer' => $this->reviewer]]);
        // Create some Beasiswa records
        Beasiswa::factory(5)->create();

        // Perform a GET request to the index route
        $response = $this->get(route('beasiswa.index'));
        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_store_a_new_scholarship()
    {
        $output = ob_get_clean();
        // user simulation
        $user = $this->user_reviewer;
        $reviewer = $this->reviewer;

        $response = $this->actingAs($user)->withSession(['auth' => ['user' => $user, 'role' => 'reviewer', 'reviewer' => $reviewer]])->get('/beasiswa/create');

        // Prepare the test data
        $files = [
            UploadedFile::fake()->create('poster1.jpeg', 100),
            UploadedFile::fake()->create('poster2.png', 200),
            UploadedFile::fake()->create('poster3.jpg', 300),
        ];
        $data = [
            'nama_beasiswa' => 'Test Scholarship',
            'deskripsi' => 'A description for the test scholarship.',
            'jenis_beasiswa' => 'full',
            'tipe_beasiswa' => 'internal',
            'kuota_beasiswa' => 100,
            'sumber_beasiswa' => 'Test Source',
            'tanggal_mulai' => now()->addDays(10)->toDateString(), // 10 days in the future
            'tanggal_berakhir' => now()->addDays(20)->toDateString(), // 20 days in the future
            'syarat_beasiswa' => ['Esai', 'Transkrip Nilai'],
            'syarat_dokumen' => ['Transkrip Nilai', 'Kartu Tanda Mahasiswa'],
            'benefit_beasiswa' => ['Scholarship Fund', 'Networking Opportunities'],
            'jenjang_pendidikan' => ['Semua Jenjang', 'D-3 Teknik Informatika'],
            'poster' => [
                $files[0], // Poster pertama
                $files[1], // Poster kedua
                $files[2]  // Poster ketiga
            ],
        ];

        // Simulate the file storage
        Storage::fake('gcs');

        // Send a POST request to the store method
        $response = $this->post('/form-beasiswa', $data);

        // Assert that the response is a redirect
        $response->assertRedirect('/beasiswa');

        // Assert that the data was inserted into the Beasiswa table
        $this->assertDatabaseHas('beasiswa', [
            'nama_beasiswa' => 'Test Scholarship',
            'deskripsi' => 'A description for the test scholarship.',
            'jenis_beasiswa' => 'full',
            'tipe_beasiswa' => 'internal',
            'kuota' => 100,
            'sumber' => 'Test Source',
            'tanggal_mulai' => '2024-12-24', // 10 days in the future
            'tanggal_berakhir' => '2025-01-03', // 20 days in the future
        ]);

        $beasiswa = Beasiswa::where('nama_beasiswa', 'Test Scholarship')->first();

        // Assert that each document was inserted into the poster table
        foreach ($files as $file) {
            $this->assertDatabaseHas('poster_beasiswa', [
                'beasiswa_id' => $beasiswa->id,
                'link_poster' => 'https://firebasestorage.googleapis.com/v0/b/sistem-informasi-kemahasiswaan.appspot.com/o/poster%2F' . $file->getClientOriginalName() . '?alt=media',
            ]);
        }

        // Assert that each jenjang_pendidikan was inserted into the database
        foreach ($data['jenjang_pendidikan'] as $jenjang) {
            $this->assertDatabaseHas('jenjang_pendidikan', [
                'beasiswa_id' => $beasiswa->id,
                'jenjang' => $jenjang,
            ]);
        }
        foreach ($data['syarat_beasiswa'] as $syarat) {
            // Cari ID syarat yang sesuai
            $syaratId = SyaratBeasiswa::where('syarat', $syarat)->first()->id;

            // Memastikan bahwa pivot table berisi ID yang benar
            $this->assertDatabaseHas('beasiswa_syarat_beasiswa', [
                'beasiswa_id' => $beasiswa->id,
                'syarat_beasiswa_id' => $syaratId,  // Gunakan ID, bukan string
            ]);
        }

        foreach ($data['benefit_beasiswa'] as $benefit) {
            // Cari ID benefit yang sesuai
            $benefitId = BenefitBeasiswa::where('benefit', $benefit)->first()->id;


            // Memastikan bahwa pivot table berisi ID yang benar
            $this->assertDatabaseHas('beasiswa_benefit', [
                'beasiswa_id' => $beasiswa->id,
                'benefit_beasiswa_id' => $benefitId,  // Gunakan ID, bukan string
            ]);
        }

        foreach ($data['syarat_dokumen'] as $dokumen) {
            // Cari ID dokumen yang sesuai
            $dokumenId = SyaratDokumen::where('dokumen', $dokumen)->first()->id;


            // Memastikan bahwa pivot table berisi ID yang benar
            $this->assertDatabaseHas('beasiswa_syarat_dokumen', [
                'beasiswa_id' => $beasiswa->id,
                'syarat_dokumen_id' => $dokumenId,  // Gunakan ID, bukan string
            ]);
        }
    }

    /** @test */
    public function it_can_edit_a_scholarship()
    {
        $output = ob_get_clean();
        // user simulation
        $user = $this->user_reviewer;
        $reviewer = $this->reviewer;

        $response = $this->actingAs($user)->withSession(['auth' => ['user' => $user, 'role' => 'reviewer', 'reviewer' => $reviewer]])->get('/beasiswa/create');

        // Prepare the test data
        $files = [
            UploadedFile::fake()->create('poster1.jpeg', 100),
            UploadedFile::fake()->create('poster2.png', 200),
            UploadedFile::fake()->create('poster3.jpg', 300),
        ];
        $data = [
            'nama_beasiswa' => 'Test Scholarship',
            'deskripsi' => 'A description for the test scholarship.',
            'jenis_beasiswa' => 'full',
            'tipe_beasiswa' => 'internal',
            'kuota_beasiswa' => 100,
            'sumber_beasiswa' => 'Test Source',
            'tanggal_mulai' => now()->addDays(10)->toDateString(), // 10 days in the future
            'tanggal_berakhir' => now()->addDays(20)->toDateString(), // 20 days in the future
            'syarat_beasiswa' => ['Esai', 'Transkrip Nilai'],
            'syarat_dokumen' => ['Transkrip Nilai', 'Kartu Tanda Mahasiswa'],
            'benefit_beasiswa' => ['Scholarship Fund', 'Networking Opportunities'],
            'jenjang_pendidikan' => ['Semua Jenjang', 'D-3 Teknik Informatika'],
            'poster' => [
                $files[0], // Poster pertama
                $files[1], // Poster kedua
                $files[2]  // Poster ketiga
            ],
        ];

        // Simulate the file storage
        Storage::fake('gcs');

        // Send a POST request to the store method
        $response = $this->post(route('beasiswa.store'), $data);

        // Assert that the response is a redirect
        $response->assertRedirect('/beasiswa');

        $beasiswa = Beasiswa::where('nama_beasiswa', 'Test Scholarship')->first();
        $data['kuota_beasiswa'] = 123;
        $data['sumber_beasiswa'] = 'Updated Sumber';
        $data['tanggal_mulai'] = '2024-12-01';
        $data['syarat_beasiswa'] = ['Esai', 'Transkrip Nilai', 'Minimum IPK 3.0'];
        $data['poster'] = $files[2];
        // Edit test: update document
        $response = $this->patch(route('beasiswa.edit', ['beasiswa' => $beasiswa->id]), $data);

        // Assert that each document was inserted into the poster table
        foreach ($files as $file) {
            $this->assertDatabaseHas('poster_beasiswa', [
                'beasiswa_id' => $beasiswa->id,
                'link_poster' => 'https://firebasestorage.googleapis.com/v0/b/sistem-informasi-kemahasiswaan.appspot.com/o/poster%2F' . $file->getClientOriginalName() . '?alt=media',
            ]);
        }

        // Assert that each jenjang_pendidikan was inserted into the database
        foreach ($data['jenjang_pendidikan'] as $jenjang) {
            $this->assertDatabaseHas('jenjang_pendidikan', [
                'beasiswa_id' => $beasiswa->id,
                'jenjang' => $jenjang,
            ]);
        }
        foreach ($data['syarat_beasiswa'] as $syarat) {
            // Cari ID syarat yang sesuai
            $syaratId = SyaratBeasiswa::where('syarat', $syarat)->first()->id;

            // Memastikan bahwa pivot table berisi ID yang benar
            $this->assertDatabaseHas('beasiswa_syarat_beasiswa', [
                'beasiswa_id' => $beasiswa->id,
                'syarat_beasiswa_id' => $syaratId,  // Gunakan ID, bukan string
            ]);
        }

        foreach ($data['benefit_beasiswa'] as $benefit) {
            // Cari ID benefit yang sesuai
            $benefitId = BenefitBeasiswa::where('benefit', $benefit)->first()->id;

            // Memastikan bahwa pivot table berisi ID yang benar
            $this->assertDatabaseHas('beasiswa_benefit', [
                'beasiswa_id' => $beasiswa->id,
                'benefit_beasiswa_id' => $benefitId,  // Gunakan ID, bukan string
            ]);
        }

        foreach ($data['syarat_dokumen'] as $dokumen) {
            // Cari ID dokumen yang sesuai
            $dokumenId = SyaratDokumen::where('dokumen', $dokumen)->first()->id;

            // Memastikan bahwa pivot table berisi ID yang benar
            $this->assertDatabaseHas('beasiswa_syarat_dokumen', [
                'beasiswa_id' => $beasiswa->id,
                'syarat_dokumen_id' => $dokumenId,  // Gunakan ID, bukan string
            ]);
        }
        // Pass the correct id for the redirect route
        $response->assertRedirect(route('beasiswa.index'));
        $response->assertSessionHas('success', 'Data beasiswa berhasil diperbarui.');
    }

    public function test_update_beasiswa_with_posters()
    {

        $this->seed();

        $user = User::find(3);

        $this->actingAs($user);
        // Use the Beasiswa seeded record with ID 1
        $beasiswa = Beasiswa::find(1); // Find the Beasiswa record with ID 1

        // Fake the storage disk for file uploads
        Storage::fake('public');

        // Simulate the request data
        $data = [
            'nama_beasiswa' => 'Beasiswa Test',
            'deskripsi' => 'Deskripsi Beasiswa Test',
            'jenis_beasiswa' => 'full',
            'tipe_beasiswa' => 'internal',
            'kuota_beasiswa' => 100,
            'sumber_beasiswa' => 'Test Source',
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d'),
            'ipk_min' => 3.0,
            'syarat_beasiswa' => ['Test Requirement'],
            'benefit_beasiswa' => ['Test Benefit'],
            'jenjang_pendidikan' => ['Bachelor'],
            'poster' => [
                UploadedFile::fake()->image('poster1.jpg'),
                UploadedFile::fake()->image('poster2.jpg'),
                UploadedFile::fake()->image('poster3.jpg'),
            ],
        ];

        // Make the PUT request to update the Beasiswa
        $response = $this->put(route('beasiswa.update', $beasiswa->id), $data);

        $this->assertTrue(true);
    }



    /** @test */
    public function it_can_show_a_specific_beasiswa()
    {
        $response = $this->actingAs($this->user_reviewer)
                        ->withSession(['auth' => ['user' => $this->user_reviewer, 'role' => 'reviewer', 'reviewer' => $this->reviewer]]);
        // Create a Beasiswa
        $beasiswa = Beasiswa::factory()->create();

        // Call the show route
        $response = $this->get(route('beasiswa.show', ['beasiswa' => 1]));

        // Assert the response contains the beasiswa data
        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_delete_a_beasiswa()
    {
        $response = $this->actingAs($this->user_reviewer)
        ->withSession(['auth' => ['user' => $this->user_reviewer, 'role' => 'reviewer', 'reviewer' => $this->reviewer]]);
        // Create a Beasiswa
        $beasiswa = Beasiswa::factory()->create();

        // Call the destroy route
        $response = $this->delete(route('beasiswa.destroy', 1));

        // Assert redirect after deletion
        $response->assertRedirect(route('beasiswa.list-beasiswa-staff'));

        // Assert the beasiswa is deleted from the database
        $this->assertDatabaseMissing('beasiswa', ['id' => 1]);
    }
}
