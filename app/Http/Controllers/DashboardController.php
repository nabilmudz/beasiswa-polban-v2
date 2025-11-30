<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use App\Models\Jurusan;
use App\Models\PenerimaBeasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = now()->year;
        $jurusan = $request->input('nama_jurusan') ?? DB::table('jurusan')->value('nama_jurusan');

        $data = $this->getBeasiswaStatistics();
        $pengajuanTahun = $this->getPengajuanStatistics($jurusan, $currentYear);
        $beasiswa = $this->getOngoingBeasiswa();
        $jurusan = Jurusan::all();

        $jmlPenerimamhs = PenerimaBeasiswa::count();
        $jmlPenerimaHistory = DB::table('history_mahasiswa_penerima')->count();
        $jmlPenerima = $jmlPenerimamhs + $jmlPenerimaHistory;



        return view('pages.Beasiswa.dashboard', compact('data', 'beasiswa', 'pengajuanTahun', 'jurusan', 'jmlPenerima'));
    }

    private function getBeasiswaStatistics()
    {
        return DB::table('beasiswa as b')
            ->selectRaw('
                COUNT(DISTINCT b.id) AS total_beasiswa,
                COUNT(DISTINCT CASE WHEN now() < b.tanggal_berakhir THEN b.id END) AS beasiswa_on_going,
                COUNT(CASE WHEN pb.status = 1 THEN 1 END) AS pengajuan_diajukan,
                COUNT(CASE WHEN pb.status IN (8, 2, 4, 6) THEN 1 END) AS pengajuan_diproses,
                COUNT(CASE WHEN pb.status = 11 THEN 1 END) AS pengajuan_ditolak,
                COUNT(CASE WHEN pb.status = 10 THEN 1 END) AS pengajuan_diterima,
                COUNT(CASE WHEN pb.status IN (3, 5, 7, 9) THEN 1 END) AS pengajuan_direvisi,
                COUNT(pb.id) AS total_pengajuan,
                SUM(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = EXTRACT(YEAR FROM CURRENT_DATE) THEN 1 ELSE 0 END) AS pengajuan_tahun_ini,
                SUM(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = EXTRACT(YEAR FROM CURRENT_DATE) - 1 THEN 1 ELSE 0 END) AS pengajuan_tahun_lalu,
                SUM(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = EXTRACT(YEAR FROM CURRENT_DATE) - 2 THEN 1 ELSE 0 END) AS pengajuan_2_tahun_lalu,
                SUM(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = EXTRACT(YEAR FROM CURRENT_DATE) - 3 THEN 1 ELSE 0 END) AS pengajuan_3_tahun_lalu,
                SUM(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = EXTRACT(YEAR FROM CURRENT_DATE) - 4 THEN 1 ELSE 0 END) AS pengajuan_4_tahun_lalu
            ')
            ->leftJoin('pengajuan_beasiswa as pb', 'b.id', '=', 'pb.beasiswa_id')
            ->first();
    }

    private function getPengajuanStatistics($jurusan, $currentYear)
    {
        return DB::table('pengajuan_beasiswa as pb')
            ->selectRaw('
                COUNT(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = ? THEN 1 END) as jumlah_tahun_sekarang,
                COUNT(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = ? THEN 1 END) as jumlah_tahun_lalu,
                COUNT(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = ? THEN 1 END) as jumlah_2_tahun_lalu,
                COUNT(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = ? THEN 1 END) as jumlah_3_tahun_lalu,
                COUNT(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = ? THEN 1 END) as jumlah_4_tahun_lalu,
                COUNT(CASE WHEN EXTRACT(YEAR FROM pb.tanggal_pengajuan) = ? THEN 1 END) as jumlah_5_tahun_lalu
            ', [
                $currentYear,
                $currentYear - 1,
                $currentYear - 2,
                $currentYear - 3,
                $currentYear - 4,
                $currentYear - 5
            ])
            ->leftJoin('mahasiswa as m', 'm.nim', '=', 'pb.nim')
            ->leftJoin('prodi as p', 'p.id', '=', 'm.prodi_id')
            ->leftJoin('jurusan as j', 'j.id', '=', 'p.jurusan_id')
            ->where('j.nama_jurusan', '=', $jurusan)
            ->first();
    }

    private function getOngoingBeasiswa()
    {
        return Beasiswa::where('tanggal_berakhir', '>=', now())
            ->where('tanggal_mulai', '<=', now())
            ->paginate(6);

        $jurusan = DB::table('jurusan')->selectRaw('nama_jurusan')->get();

        $jmlPenerima = PenerimaBeasiswa::count();

        return view('pages.Beasiswa.dashboard', compact('data', 'beasiswa', 'data1', 'jurusan', 'jmlPenerima'));
    }
}
