<?php

namespace Database\Factories;

use App\Models\Beasiswa;
use App\Models\SyaratBeasiswa;
use App\Models\SyaratDokumen;
use App\Models\JenjangPendidikan;
use App\Models\BenefitBeasiswa;
use App\Models\PosterBeasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BeasiswaFactory extends Factory
{
    protected $model = Beasiswa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $id = 2; // Mulai ID dari 2

        return [
            'id' => $id++, // Tetapkan ID secara increment
            'nama_beasiswa' => 'LKPD' . ' Scholarship',
            'deskripsi' => 'Beasiswa LPDP adalah program beasiswa yang dibiayai oleh pemerintah, dan dikelola oleh LPDP (Lembaga Pengelola Dana Pendidikan). Beasiswa LPDP ini diberikan khusus kepada mereka yang ingin melanjutkan pendidikan ke jenjang magister (S2) atau doktor (S3).', // Menghasilkan 3 kalimat

            'sumber' => 'KEMENDIKBUD',
            'tipe_beasiswa' => $this->faker->randomElement([
                'kipk',
                'internal',
                'eksternal',
            ]),
            'kuota' => $this->faker->numberBetween(1, 100),
            'jenis_beasiswa' => $this->faker->randomElement(['full', 'half']),
            'tanggal_mulai' => $this->faker->date(),
            'tanggal_berakhir' => $this->faker->date(),
        ];
    }

    /**
     * Configure the factory to include related models.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Beasiswa $beasiswa) {
            // Generate related Syarat Beasiswa and attach them
            $syaratBeasiswas = SyaratBeasiswa::factory()->count(2)->create();
            $beasiswa->syaratBeasiswa()->attach($syaratBeasiswas->pluck('id')->toArray());
            $syaratDokumen = SyaratDokumen::factory()->count(2)->create();
            $beasiswa->syaratDokumen()->attach($syaratDokumen->pluck('id')->toArray());
            $benefitBeasiswas = BenefitBeasiswa::factory()->count(2)->create();
            $beasiswa->benefitBeasiswa()->attach($benefitBeasiswas->pluck('id')->toArray());

            // Generate related Posters
            Storage::fake('gcs'); // Simulate cloud storage

            foreach (['poster1.jpg', 'poster2.jpg'] as $posterName) {
                $path = UploadedFile::fake()->image($posterName)->store('posters', 'gcs');
                PosterBeasiswa::create([
                    'beasiswa_id' => $beasiswa->id,
                    'link_poster' => Storage::disk('gcs')->url($path),
                ]);
            }
        });
    }

}

