<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\beasiswa;
use App\Models\SyaratDokumen;
use App\Models\BenefitBeasiswa;
use App\Models\SyaratBeasiswa;
use App\Models\JenjangPendidikan;
use App\Models\PosterBeasiswa;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BeasiswaTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_beasiswa_with_existing_syarat_benefit_dokumen(): void
    {

        $this->seed(); // Seed the database, if needed

        // Simulate the file upload using fake storage
        Storage::fake('gcs');

        // Prepare the request data with multiple files
        $files = [
            UploadedFile::fake()->create('poster1.pdf', 100),
            UploadedFile::fake()->create('poster2.pdf', 200),
            UploadedFile::fake()->create('poster3.pdf', 300),
        ];

        // Prepare the request data
        $data = [
            'nama_beasiswa' => 'Create Beasiswa Test',
            'sumber_beasiswa' => 'Polban',
            'deskripsi' => 'Test Beasiswa',
            'jenis_beasiswa' => 'full',
            'tipe_beasiswa' => 'internal',
            'kuota_beasiswa' => 10,
            'tanggal_mulai' => '2024-01-01',
            'tanggal_berakhir' => '2024-12-31',
            'syarat_beasiswa' => ['IPK minimal 3.5', 'Mahasiswa aktif', 'Surat rekomendasi'],
            'syarat_dokumen' => ['KTP', 'Kartu Mahasiswa', 'Transkrip Nilai'],
            'benefit_beasiswa' => ['Uang saku', 'Pembebasan biaya kuliah', 'Asrama gratis'],
            'jenjang_pendidikan' => ['Semua Jenjang', 'D-4 Teknik Informatika'],
            'poster' => [
                $files[0], // Poster pertama
                $files[1], // Poster kedua
                $files[2]  // Poster ketiga
            ],
        ];

        // Call the store method
        $response = $this->post(route('beasiswa.store', ['id' => 1]), $data);

        // Assert the redirect and success message
        $response->assertRedirect(route('beasiswa'));
        $response->assertSessionHas('success', 'Beasiswa berhasil ditambahkan');

        // Assert that the data was inserted into the Beasiswa table
        $this->assertDatabaseHas('beasiswa', [
            'nama_beasiswa' => 'Create Beasiswa Test',
            'sumber_beasiswa' => 'Polban',
            'deskripsi' => 'Test Beasiswa',
            'jenis_beasiswa' => 'full',
            'tipe_beasiswa' => 'internal',
            'kuota_beasiswa' => 10,
            'tanggal_mulai' => '2024-01-01',
            'tanggal_berakhir' => '2024-12-31',
        ]);

        // Assert that each document was inserted into the poster table
        foreach ($files as $file) {
            $this->assertDatabaseHas('poster_beasiswa', [
                'beasiswa_id' => Beasiswa::first()->id,
                'link_dokumen' => 'https://firebasestorage.googleapis.com/v0/b/sistem-informasi-kemahasiswaan.appspot.com/o/dokumen%2F' . $file->getClientOriginalName() . '?alt=media',
            ]);
        }

        // Assert that each jenjang_pendidikan was inserted into the database
        foreach ($data['jenjang_pendidikan'] as $jenjang) {
            $this->assertDatabaseHas('jenjang_pendidikan', [
                'beasiswa_id' => Beasiswa::first()->id,
                'nama_jenjang' => $jenjang,
            ]);
        }
        // Assert that each syarat_beasiswa was inserted into the pivot table
        foreach ($data['syarat_beasiswa'] as $syarat) {
            $this->assertDatabaseHas('beasiswa_syarat_beasiswa', [
                'beasiswa_id' => Beasiswa::first()->id,
                'syarat' => $syarat,
            ]);
        }
        // Assert that each benefit_beasiswa was inserted into the pivot table
        foreach ($data['benefit_beasiswa'] as $benefit) {
            $this->assertDatabaseHas('beasiswa_benefit', [
                'beasiswa_id' => Beasiswa::first()->id,
                'benefit' => $benefit,
            ]);
        }
        // Assert that each syarat_dokumen was inserted into the pivot table
        foreach ($data['syarat_dokumen'] as $dokumen) {
            $this->assertDatabaseHas('beasiswa_syarat_dokumen', [
                'beasiswa_id' => Beasiswa::first()->id,
                'dokumen' => $dokumen,
            ]);
        }


    }
}
