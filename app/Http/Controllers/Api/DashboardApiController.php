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

    // GET /api/submissions/pending  (staff only)
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

    // Helper: convert status id → label spec
    private function resolveStatusLabel(int $statusId, array $map): string
    {
        foreach ($map as $label => $ids) {
            if (in_array($statusId, $ids)) return $label;
        }
        return 'PENDING';
    }
}