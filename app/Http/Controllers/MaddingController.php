<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\PenerimaBeasiswa;
use App\Models\HistoryMahasiswaPenerima; // Pastikan model ini di-import
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaddingController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $newestBeasiswa = $this->getNewestBeasiswa($today);
        $upcomingBeasiswa = $this->getUpcomingBeasiswa($today);
        $mahasiswaAccepted = $this->getAcceptedMahasiswaFromSystem();

        if ($mahasiswaAccepted->isEmpty()) {
            $mahasiswaAccepted = $this->getAcceptedMahasiswaFromHistory();
        }

        return view('pages.Madding.madding', compact('newestBeasiswa', 'upcomingBeasiswa', 'mahasiswaAccepted'));
    }

    public function landing()
    {
        $today = Carbon::today();

        $newestBeasiswa = $this->getNewestBeasiswa($today);
        $upcomingBeasiswa = $this->getUpcomingBeasiswa($today);
        $mahasiswaAccepted = $this->getAcceptedMahasiswaFromSystem();

        if ($mahasiswaAccepted->isEmpty()) {
            $mahasiswaAccepted = $this->getAcceptedMahasiswaFromHistory();
        }

        return view('landing', compact('newestBeasiswa', 'upcomingBeasiswa', 'mahasiswaAccepted'));
    }



    private function getAcceptedMahasiswaFromSystem()
    {
        return PenerimaBeasiswa::join('beasiswa', 'beasiswa.id', '=', 'penerima_beasiswa.beasiswa_id')
            ->join('mahasiswa', 'penerima_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->select(
                'users.foto',
                'users.nama_depan',
                'users.nama_belakang',
                'mahasiswa.angkatan',
                'prodi.nama_prodi',
                'beasiswa.nama_beasiswa',
                'penerima_beasiswa.created_at as tanggal_diterima',
                DB::raw("'sistem' as sumber_data")
            )
            ->whereMonth('penerima_beasiswa.created_at', now()->month)
            ->whereYear('penerima_beasiswa.created_at', now()->year)
            ->inRandomOrder()
            ->paginate(4);
    }

    private function getAcceptedMahasiswaFromHistory()
    {
        $query = HistoryMahasiswaPenerima::query();

        // Gunakan LEFT JOIN untuk memastikan baris history tetap ada
        // meskipun data mahasiswa atau user tidak ditemukan.
        $query->leftJoin('mahasiswa', 'history_mahasiswa_penerima.nim', '=', 'mahasiswa.nim')
              ->leftJoin('users', 'mahasiswa.user_id', '=', 'users.id');

        $query->select(
            DB::raw("COALESCE(users.foto, 'default-profile.png') as foto"), // Sediakan path default image Anda
            DB::raw("COALESCE(users.nama_depan, SPLIT_PART(history_mahasiswa_penerima.nama_mahasiswa, ' ', 1)) as nama_depan"),
            DB::raw("COALESCE(users.nama_belakang,
                        CASE
                            WHEN POSITION(' ' IN history_mahasiswa_penerima.nama_mahasiswa) > 0
                            THEN SUBSTRING(history_mahasiswa_penerima.nama_mahasiswa, POSITION(' ' IN history_mahasiswa_penerima.nama_mahasiswa) + 1)
                            ELSE ''
                        END) as nama_belakang"),
            // Untuk angkatan, jika NULL dan ingin ditampilkan sebagai 'N/A', cast ke VARCHAR
            DB::raw("COALESCE(CAST(mahasiswa.angkatan AS CHAR), 'N/A') as angkatan"),
            'history_mahasiswa_penerima.nama_prodi',
            'history_mahasiswa_penerima.nama_beasiswa',
            'history_mahasiswa_penerima.created_at as tanggal_diterima',
            DB::raw("'history' as sumber_data")
        );

        // Kriteria fallback untuk history (misalnya, data dari 1 tahun terakhir)
        $query->where('history_mahasiswa_penerima.created_at', '>=', now()->subYear());

        return $query->inRandomOrder()
                     ->paginate(4);
    }

    private function getNewestBeasiswa($today)
    {
        $beasiswa = Beasiswa::leftJoin('jenjang_pendidikan', 'jenjang_pendidikan.beasiswa_id', '=', 'beasiswa.id')
            ->leftJoin('poster_beasiswa', 'poster_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->select(
                'poster_beasiswa.link_poster', 'beasiswa.id', 'beasiswa.nama_beasiswa', 'beasiswa.deskripsi',
                'beasiswa.tipe_beasiswa', 'beasiswa.jenis_beasiswa', 'beasiswa.kuota', 'beasiswa.sumber',
                'beasiswa.tanggal_mulai', 'beasiswa.tanggal_berakhir',
                DB::raw("COALESCE(CONCAT('{', STRING_AGG(CONCAT('\"', jenjang_pendidikan.jenjang, '\"'), ','), '}'), '{\"All Jenjang Pendidikan\"}') AS jenjang_list")
            )
            ->where('beasiswa.tanggal_mulai', '<=', $today)
            ->where('beasiswa.tanggal_berakhir', '>=', $today)
            ->groupBy('beasiswa.id', 'poster_beasiswa.link_poster')
            ->orderBy('beasiswa.tanggal_mulai', 'desc')
            ->take(7)
            ->get();
        return $this->transformBeasiswa($beasiswa);
    }

    private function getUpcomingBeasiswa($today)
    {
        $beasiswa = Beasiswa::leftJoin('jenjang_pendidikan', 'jenjang_pendidikan.beasiswa_id', '=', 'beasiswa.id')
            ->leftJoin('poster_beasiswa', 'poster_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->select(
                'poster_beasiswa.link_poster', 'beasiswa.id', 'beasiswa.nama_beasiswa', 'beasiswa.deskripsi',
                'beasiswa.tipe_beasiswa', 'beasiswa.jenis_beasiswa', 'beasiswa.kuota', 'beasiswa.sumber',
                'beasiswa.tanggal_mulai', 'beasiswa.tanggal_berakhir',
                DB::raw("COALESCE(CONCAT('{', STRING_AGG(CONCAT('\"', jenjang_pendidikan.jenjang, '\"'), ','), '}'), '{\"All Jenjang Pendidikan\"}') AS jenjang_list")
            )
            ->where('beasiswa.tanggal_mulai', '>', $today)
            ->groupBy('beasiswa.id', 'poster_beasiswa.link_poster')
            ->orderBy('beasiswa.tanggal_mulai', 'asc')
            ->take(7)
            ->get();
        return $this->transformBeasiswa($beasiswa);
    }

    private function transformBeasiswa($beasiswaCollection)
    {
        if ($beasiswaCollection->isEmpty()) {
            return collect();
        }
        return $beasiswaCollection->transform(function ($item) {
            $item->short_description = property_exists($item, 'deskripsi') && !is_null($item->deskripsi)
                ? Str::limit($item->deskripsi, 100, '...')
                : 'Deskripsi tidak tersedia.';
            $item->jenjang_list = property_exists($item, 'jenjang_list') && is_string($item->jenjang_list) && !is_null($item->jenjang_list)
                ? $this->processJenjangList($item->jenjang_list)
                : ['Semua Jenjang'];
            return $item;
        });
    }

    private function processJenjangList($jenjangList)
    {
        if (empty($jenjangList) || !is_string($jenjangList)) {
            return ['Semua Jenjang'];
        }
        $cleanedList = trim($jenjangList, '{}');
        if (empty($cleanedList)) {
             if (str_replace(',', '', $cleanedList) === '') {
                return ['Semua Jenjang'];
            }
        }
        $jenjangArray = explode(',', $cleanedList);
        $filteredJenjangArray = array_filter($jenjangArray, fn($value) => !is_null($value) && $value !== '');
        if (empty($filteredJenjangArray)) {
            return ['Semua Jenjang'];
        }
        return array_map(fn($jenjang) => trim($jenjang, '"'), $filteredJenjangArray);
    }
}
