<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use App\Models\PengajuanBeasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    private string $appSource = 'BEASISWA';

    
    // =========================================================
    // GET /timelines
    // =========================================================
    public function timelines(Request $request)
    {
        $from       = $request->query('from', now()->toDateString());
        $to         = $request->query('to', now()->addDays(90)->toDateString());
        $activeOnly = $request->boolean('activeOnly', false);

        // Validasi tanggal
        if ($from > $to) {
            return response()->json([
                'status'    => 400,
                'errorCode' => 'INVALID_DATE_RANGE',
                'message'   => "'from' tidak boleh lebih besar dari 'to'",
                'timestamp' => now()->toIso8601String(),
            ], 400);
        }

        $query = Beasiswa::where('publish', true)
            ->whereBetween('tanggal_mulai', [$from, $to])
            ->orWhereBetween('tanggal_berakhir', [$from, $to]);

        if ($activeOnly) {
            $today = now()->toDateString();
            $query->where('tanggal_mulai', '<=', $today)
                  ->where('tanggal_berakhir', '>=', $today);
        }

        $beasiswaList = $query->get();

        $data = $beasiswaList->map(function ($b) {
            $today = now()->toDateString();
            $isActive = $b->tanggal_mulai <= $today && $b->tanggal_berakhir >= $today;

            return [
                'programId'   => $b->id,
                'programName' => $b->nama_beasiswa,
                'phase'       => 'Pendaftaran',
                'startDate'   => $b->tanggal_mulai,
                'endDate'     => $b->tanggal_berakhir,
                'isActive'    => $isActive,
                'deepLinkUrl' => url('/beasiswa/' . $b->id),
            ];
        });

        return response()->json([
            'appSource' => $this->appSource,
            'data'      => $data,
        ]);
    }

    
    // =========================================================
    // GET /opportunities  (info beasiswa aktif, publik)
    // =========================================================
    public function opportunities(Request $request)
    {
        $isOpen   = $request->boolean('isOpen', true);
        $page     = max(1, (int) $request->query('page', 1));
        $pageSize = min(50, max(1, (int) $request->query('pageSize', 10)));

        $today = now()->toDateString();
        $query = Beasiswa::where('publish', true);

        if ($isOpen) {
            $query->where('tanggal_mulai', '<=', $today)
                  ->where('tanggal_berakhir', '>=', $today);
        }

        $paginated = $query->latest()->paginate($pageSize, ['*'], 'page', $page);

        $data = collect($paginated->items())->map(function ($b) use ($today) {
            $isOpen = $b->tanggal_mulai <= $today && $b->tanggal_berakhir >= $today;
            return [
                'opportunityId'     => $b->id,
                'title'             => $b->nama_beasiswa,
                'category'          => 'BEASISWA',
                'description'       => $b->deskripsi,
                'registrationStart' => $b->tanggal_mulai,
                'registrationEnd'   => $b->tanggal_berakhir,
                'isOpen'            => $isOpen,
                'deepLinkUrl'       => url('/beasiswa/' . $b->id),
            ];
        });

        return response()->json([
            'appSource' => $this->appSource,
            'data'      => $data,
            'meta'      => [
                'page'        => $paginated->currentPage(),
                'pageSize'    => $paginated->perPage(),
                'totalItems'  => $paginated->total(),
                'totalPages'  => $paginated->lastPage(),
                'hasNextPage' => $paginated->hasMorePages(),
            ],
        ]);
    }
}