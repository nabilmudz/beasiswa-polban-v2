<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function programStatus()
    {
        $data = Cache::remember('program_status', 300, function () {
            $today = now()->toDateString();

            return DB::table('beasiswa')
                ->select(
                    'id',
                    'nama_beasiswa as nama_program',
                    DB::raw("
                        CASE
                            WHEN tanggal_mulai > '{$today}' THEN 'AKAN_DATANG'
                            WHEN tanggal_berakhir < '{$today}' THEN 'SELESAI'
                            ELSE 'BERJALAN'
                        END as status
                    ")
                )
                ->get();
        });

        return response()->json($data);
    }


    public function statusAktivitas(Request $request)
    {
        $periode = $request->query('periode', date('Y'));
        $granularity = strtoupper($request->query('granularity', 'MONTHLY'));

        if (!in_array($granularity, ['DAILY', 'WEEKLY', 'MONTHLY'])) {
            return response()->json([
                'status' => 400,
                'errorCode' => 'BAD_REQUEST',
                'message' => 'Granularity harus berupa DAILY, WEEKLY, atau MONTHLY'
            ], 400);
        }

        $cacheKey = "status_aktivitas_{$periode}_{$granularity}";

        $data = Cache::remember($cacheKey, 120, function () use ($periode, $granularity) {
            
            $trunc = match($granularity) {
                'DAILY' => 'day',
                'WEEKLY' => 'week',
                'MONTHLY' => 'month',
            };


            $agregasi = DB::table('pengajuan_beasiswa')
                ->select(
                    DB::raw("DATE_TRUNC('{$trunc}', created_at) as periode_waktu"),
                    DB::raw("COUNT(*) as total_aktivitas")
                )
                ->whereYear('created_at', $periode)
                ->whereNotNull('created_at')
                ->groupBy('periode_waktu')
                ->orderBy('periode_waktu', 'asc')
                ->get();

            return $agregasi->map(function ($item) use ($granularity) {
                return [
                    'periode' => Carbon::parse($item->periode_waktu)
                                 ->format($granularity === 'MONTHLY' ? 'Y-m' : 'Y-m-d'),
                    'total' => (int) $item->total_aktivitas
                ];
            });
        });

        return response()->json($data);
    }
}