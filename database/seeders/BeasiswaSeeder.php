<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Beasiswa;
use App\Models\SyaratBeasiswa;
use App\Models\BenefitBeasiswa;
use App\Models\SyaratDokumen;
use Carbon\Carbon;

class BeasiswaSeeder extends Seeder
{
    public function run()
    {
        $currentTime = Carbon::now();

        // Beasiswa LPDP
        $beasiswa1 = Beasiswa::create([
            'nama_beasiswa' => 'Beasiswa LPDP',
            'deskripsi' => 'Beasiswa Lembaga Pengelola Dana Pendidikan (LPDP)...',
            'tipe_beasiswa' => 'eksternal',
            'jenis_beasiswa' => 'full',
            'kuota' => 100,
            'sumber' => 'KEMENDIKBUD',
            'tanggal_mulai' => '2024-01-01',
            'tanggal_berakhir' => '2024-04-30',
            'publish' => 1,
        ]);

        $syarat1 = SyaratBeasiswa::create(['syarat' => 'Memiliki ijazah S1 yang diakui.']);
        $syarat2 = SyaratBeasiswa::create(['syarat' => 'Mendaftar dalam waktu yang ditentukan.']);
        $beasiswa1->syaratBeasiswa()->attach([
            $syarat1->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
            $syarat2->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        $benefit1 = BenefitBeasiswa::create(['benefit' => 'Biaya pendidikan penuh.']);
        $benefit2 = BenefitBeasiswa::create(['benefit' => 'Biaya hidup selama masa studi.']);
        $beasiswa1->benefitBeasiswa()->attach([
            $benefit1->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
            $benefit2->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        $dokumen1 = SyaratDokumen::create(['dokumen' => 'Fotokopi ijazah terakhir.', 'link_dokumen' => 'http://127.0.0.1:8000/storage/dokumen/fau.jpeg']);
        $dokumen2 = SyaratDokumen::create(['dokumen' => 'Surat rekomendasi.', 'link_dokumen' => 'http://127.0.0.1:8000/storage/dokumen/angyfauna.jpeg']);
        $beasiswa1->syaratDokumen()->attach([
            $dokumen1->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
            $dokumen2->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        $beasiswa1->jenjangPendidikan()->create(['beasiswa_id' => $beasiswa1->id, 'jenjang' => 'D-3 Teknik Informatika']);
        $beasiswa1->posterBeasiswa()->create(['beasiswa_id' => $beasiswa1->id, 'link_poster' => 'example.jpg']);
        $beasiswa1->linkBeasiswa()->create(['beasiswa_id' => $beasiswa1->id, 'link_beasiswa' => 'https://www.beasiswalpdp.com']);

        // Beasiswa Bidikmisi
        $beasiswa2 = Beasiswa::create([
            'nama_beasiswa' => 'Beasiswa Bidikmisi',
            'deskripsi' => 'Beasiswa untuk mahasiswa kurang mampu namun berprestasi.',
            'tipe_beasiswa' => 'internal',
            'jenis_beasiswa' => 'half',
            'kuota' => 50,
            'sumber' => 'KEMENDIKBUD',
            'tanggal_mulai' => '2024-02-01',
            'tanggal_berakhir' => '2024-05-31',
            'publish' => 1,
        ]);

        $syarat3 = SyaratBeasiswa::create(['syarat' => 'Mahasiswa aktif di perguruan tinggi negeri.']);
        $syarat4 = SyaratBeasiswa::create(['syarat' => 'Berpenghasilan orang tua maksimal Rp4.000.000.']);
        $beasiswa2->syaratBeasiswa()->attach([
            $syarat3->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
            $syarat4->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        $benefit3 = BenefitBeasiswa::create(['benefit' => 'Uang saku setiap semester.']);
        $benefit4 = BenefitBeasiswa::create(['benefit' => 'Biaya kuliah dibayarkan penuh.']);
        $beasiswa2->benefitBeasiswa()->attach([
            $benefit3->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
            $benefit4->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        $dokumen3 = SyaratDokumen::create(['dokumen' => 'Kartu Tanda Mahasiswa.', 'link_dokumen' => 'http://127.0.0.1:8000/storage/dokumen/ktm.jpeg']);
        $dokumen4 = SyaratDokumen::create(['dokumen' => 'Surat keterangan kurang mampu.', 'link_dokumen' => 'http://127.0.0.1:8000/storage/dokumen/skkm.jpeg']);
        $beasiswa2->syaratDokumen()->attach([
            $dokumen3->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
            $dokumen4->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        $beasiswa2->jenjangPendidikan()->create(['beasiswa_id' => $beasiswa2->id, 'jenjang' => 'Semua Jenjang']);
        $beasiswa2->posterBeasiswa()->create(['beasiswa_id' => $beasiswa2->id, 'link_poster' => 'bidikmisi.jpg']);
        $beasiswa2->linkBeasiswa()->create(['beasiswa_id' => $beasiswa2->id, 'link_beasiswa' => 'https://www.beasiswabidikmisi.com']);

        // Beasiswa Prestasi Nasional
        $beasiswa3 = Beasiswa::create([
            'nama_beasiswa' => 'Beasiswa Prestasi Nasional',
            'deskripsi' => 'Beasiswa untuk mahasiswa dengan prestasi akademik dan non-akademik terbaik.',
            'tipe_beasiswa' => 'eksternal',

            'jenis_beasiswa' => 'full',
            'kuota'=>30,
            'sumber' => 'KEMENDIKBUD',
            'tanggal_mulai' => '2024-03-01',
            'tanggal_berakhir' => '2024-06-30',
            'publish' => 0,
        ]);

        $syarat5 = SyaratBeasiswa::create(['syarat' => 'IPK minimal 3.75.']);
        $syarat6 = SyaratBeasiswa::create(['syarat' => 'Melampirkan sertifikat prestasi tingkat nasional.']);
        $beasiswa3->syaratBeasiswa()->attach([
            $syarat5->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
            $syarat6->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        $benefit5 = BenefitBeasiswa::create(['benefit' => 'Uang saku dan biaya kuliah.']);
        $beasiswa3->benefitBeasiswa()->attach([
            $benefit5->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        $dokumen5 = SyaratDokumen::create(['dokumen' => 'Transkrip nilai terakhir.', 'link_dokumen' => 'http://127.0.0.1:8000/storage/dokumen/transkrip.jpeg']);
        $dokumen6 = SyaratDokumen::create(['dokumen' => 'Fotokopi sertifikat prestasi.', 'link_dokumen' => 'http://127.0.0.1:8000/storage/dokumen/sertifikat.jpeg']);
        $beasiswa3->syaratDokumen()->attach([
            $dokumen5->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
            $dokumen6->id => ['created_at' => $currentTime, 'updated_at' => $currentTime],
        ]);

        $beasiswa3->jenjangPendidikan()->create(['beasiswa_id' => $beasiswa3->id, 'jenjang' => 'S-2 Teknik Elektro']);
        $beasiswa3->posterBeasiswa()->create(['beasiswa_id' => $beasiswa3->id, 'link_poster' => 'prestasi.jpg']);
        $beasiswa3->linkBeasiswa()->create(['beasiswa_id' => $beasiswa3->id, 'link_beasiswa' => 'https://www.beasiswaprestasinasional.com']);
    }
}
