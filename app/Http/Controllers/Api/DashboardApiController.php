<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use App\Models\PengajuanBeasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    private string $appSource = 'BEASISWA';

    // GET /api/health
    public function health()
    {
        $dbStatus = 'UP';
        $start = microtime(true);
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'DOWN';
        }

        return response()->json([
            'appSource' => $this->appSource,
            'status'    => $dbStatus === 'UP' ? 'UP' : 'DOWN',
            'version'   => config('app.version', '1.0.0'),
            'timestamp' => now()->toIso8601String(),
            'dbStatus'  => $dbStatus,
            'latencyMs' => round((microtime(true) - $start) * 1000),
        ], $dbStatus === 'UP' ? 200 : 503);
    }

    // GET /api/submissions
    public function submissions(Request $request)
    {
        $userId   = $request->jwt_user_id;
        $nim      = $request->jwt_nim;
        $status   = $request->query('status');
        $page     = max(1, (int) $request->query('page', 1));
        $pageSize = min(50, max(1, (int) $request->query('pageSize', 10)));

        $validStatuses = ['PENDING', 'REVIEWED', 'APPROVED', 'REJECTED', 'REVISION'];
        if ($status && !in_array($status, $validStatuses)) {
            return response()->json([
                'status'    => 400,
                'errorCode' => 'INVALID_STATUS',
                'message'   => 'Nilai status tidak valid. Gunakan: ' . implode('|', $validStatuses),
                'timestamp' => now()->toIso8601String(),
            ], 400);
        }

        // Mapping status spec → id kode_status di database
        $statusIdMap = [
            'PENDING'  => [1],
            'REVIEWED' => [2, 4, 6],
            'REVISION' => [3, 5, 7],
            'APPROVED' => [8],
            'REJECTED' => [9],
        ];

        $query = PengajuanBeasiswa::with(['Beasiswa', 'Status'])
            ->where(function ($q) use ($userId, $nim) {
                if ($userId) $q->orWhere('user_id_pengaju', $userId);
                if ($nim)    $q->orWhere('nim', $nim);
            });

        if ($status) {
            $ids = $statusIdMap[$status];
            $query->whereIn('status', $ids);
        }

        $paginated = $query->latest()->paginate($pageSize, ['*'], 'page', $page);

        $data = collect($paginated->items())->map(function ($p) use ($statusIdMap) {
            $statusLabel = $this->resolveStatusLabel($p->status, $statusIdMap);
            return [
                'submissionId'  => $p->id,
                'title'         => $p->Beasiswa?->nama_beasiswa ?? 'N/A',
                'status'        => $statusLabel,
                'submittedAt'   => $p->tanggal_pengajuan
                                    ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->toIso8601String()
                                    : $p->created_at?->toIso8601String(),
                'lastUpdatedAt' => $p->updated_at?->toIso8601String(),
                'currentStage'  => $p->Status?->isi_status ?? null,
                'deepLinkUrl'   => url('/pengajuan-beasiswa/' . $p->id),
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

    // GET /api/submissions/pending
    public function submissionsPending(Request $request)
    {
        $page     = max(1, (int) $request->query('page', 1));
        $pageSize = min(100, max(1, (int) $request->query('pageSize', 20)));
        $stage    = $request->query('stage');

        // Pending = status belum final (id 1-7)
        $query = PengajuanBeasiswa::with(['Beasiswa', 'Mahasiswa', 'Status', 'Pengaju'])
            ->whereIn('status', [1, 2, 3, 4, 5, 6, 7]);

        if ($stage) {
            $query->whereHas('Status', function ($q) use ($stage) {
                $q->where('isi_status', 'like', "%{$stage}%");
            });
        }

        $statusIdMap = [
            'PENDING'  => [1],
            'REVIEWED' => [2, 4, 6],
            'REVISION' => [3, 5, 7],
            'APPROVED' => [8],
            'REJECTED' => [9],
        ];

        $paginated = $query->latest()->paginate($pageSize, ['*'], 'page', $page);

        $data = collect($paginated->items())->map(function ($p) use ($statusIdMap) {
            $mahasiswa     = $p->Mahasiswa;
            $pengaju       = $p->Pengaju;
            $statusLabel   = $this->resolveStatusLabel($p->status, $statusIdMap);

            return [
                'submissionId'  => $p->id,
                'title'         => $p->Beasiswa?->nama_beasiswa ?? 'N/A',
                'submitterName' => $mahasiswa?->nama ?? $pengaju?->name ?? 'N/A',
                'submitterNim'  => $p->nim ?? 'N/A',
                'status'        => $statusLabel,
                'submittedAt'   => $p->tanggal_pengajuan
                                    ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->toIso8601String()
                                    : $p->created_at?->toIso8601String(),
                'lastUpdatedAt' => $p->updated_at?->toIso8601String(),
                'currentStage'  => $p->Status?->isi_status ?? null,
                'deepLinkUrl'   => url('/review-beasiswa/' . $p->id),
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

    // GET /timelines
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

    // GET /opportunities  
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

    // GET /stats/monitoring 
    public function monitoring(Request $request)
    {
        $programId = $request->query('programId');
        $jurusan   = $request->query('jurusan');
        $periode   = $request->query('periode');

        $baseQuery = fn() => PengajuanBeasiswa::query()
            ->join('mahasiswa', 'pengajuan_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->join('jurusan', 'prodi.jurusan_id', '=', 'jurusan.id')
            ->when($periode, fn($q) => $q->whereYear('pengajuan_beasiswa.tanggal_pengajuan', $periode))
            ->when($jurusan, fn($q) => $q->where('jurusan.nama_jurusan', $jurusan))
            ->when($programId, fn($q) => $q->where('pengajuan_beasiswa.beasiswa_id', $programId));

        $totalPengajuan = $baseQuery()->count();
        $totalDiterima  = $baseQuery()->whereIn('pengajuan_beasiswa.status', [8])->count();

        $perProgram = $baseQuery()
            ->join('beasiswa', 'pengajuan_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->select(
                'beasiswa.id as programId',
                'beasiswa.nama_beasiswa as programName',
                DB::raw('COUNT(*) as totalPengajuan'),
                DB::raw('SUM(CASE WHEN pengajuan_beasiswa.status = 8 THEN 1 ELSE 0 END) as totalDiterima')
            )
            ->groupBy('beasiswa.id', 'beasiswa.nama_beasiswa')
            ->get();

        return response()->json([
            'appSource'      => $this->appSource,
            'periode'        => $periode,
            'totalPengajuan' => $totalPengajuan,
            'totalDiterima'  => $totalDiterima,
            'perProgram'     => $perProgram,
        ]);
    }

    // GET /stats/sebaran-jurusan 
    public function sebaranJurusan(Request $request)
    {
        $periode   = $request->query('periode');
        $programId = $request->query('programId');

        $data = PengajuanBeasiswa::query()
            ->join('mahasiswa', 'pengajuan_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->join('jurusan', 'prodi.jurusan_id', '=', 'jurusan.id')
            ->select('jurusan.nama_jurusan as jurusan', DB::raw('COUNT(*) as "totalPenerima"'))
            ->whereIn('pengajuan_beasiswa.status', [8])
            ->when($periode, fn($q) => $q->whereYear('pengajuan_beasiswa.tanggal_pengajuan', $periode))
            ->when($programId, fn($q) => $q->where('pengajuan_beasiswa.beasiswa_id', $programId))
            ->groupBy('jurusan.nama_jurusan')
            ->orderByDesc(DB::raw('"totalPenerima"'))
            ->get();

        return response()->json([
            'appSource' => $this->appSource,
            'data'      => $data,
        ]);
    }

    // GET /stats/sebaran-tipe-sumber
    public function sebaranTipeSumber(Request $request)
    {
        $periode = $request->query('periode');

        $byTipe = PengajuanBeasiswa::query()
            ->join('beasiswa', 'pengajuan_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->select('beasiswa.tipe_beasiswa as tipe', DB::raw('COUNT(pengajuan_beasiswa.id) as jumlah'))
            ->when($periode, fn($q) => $q->whereYear('pengajuan_beasiswa.tanggal_pengajuan', $periode))
            ->groupBy('beasiswa.tipe_beasiswa')
            ->get();

        $bySumberDana = PengajuanBeasiswa::query()
            ->join('beasiswa', 'pengajuan_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->select('beasiswa.sumber as sumber', DB::raw('COUNT(pengajuan_beasiswa.id) as jumlah'))
            ->when($periode, fn($q) => $q->whereYear('pengajuan_beasiswa.tanggal_pengajuan', $periode))
            ->groupBy('beasiswa.sumber')
            ->get();

        return response()->json([
            'appSource'    => $this->appSource,
            'byTipe'       => $byTipe,
            'bySumberDana' => $bySumberDana,
        ]);
    }

    // Helper: convert status id → label spec
    private function resolveStatusLabel(int $statusId, array $map): string
    {
        foreach ($map as $label => $ids) {
            if (in_array($statusId, $ids)) return $label;
        }
        return 'PENDING';
    }
}