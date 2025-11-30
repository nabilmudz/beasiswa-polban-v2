<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MailController;
use App\Mail\NotificationMail;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\beasiswa;
use App\Models\KodeStatus;
use App\Models\PengajuanBeasiswa;
use App\Models\PengajuanDokumen;
use App\Models\Prodi;
use App\Models\Reviewer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PengajuanBeasiswaController extends Controller
{
    public function listPengajuanStaff()
    {
        $user = Auth::user();
        $listPengajuan = $this->getListPengajuan($user);
        $namaBeasiswa = Beasiswa::pluck('nama_beasiswa');

        return view('pages.Beasiswa.list-pengaju-beasiswa', compact('listPengajuan','namaBeasiswa'));
    }


    public function create(string $id)
    {
        $user = Auth::user();
        $mhs = $this->getMahasiswaByUserId($user->id);
        $prodi = $this->getProdiById($mhs->prodi_id);
        $jurusan = $this->getJurusanById($prodi->jurusan_id);
        $dokumen = $this->getDokumenByBeasiswaId($id);

        return view('pages.Beasiswa.pengajuan-beasiswa', [
            'user' => $user,
            'pengajuan' => null,
            'dokumenPengajuan' => null,
            'mhs' => $mhs,
            'jurusan' => $jurusan,
            'prodi' => $prodi,
            'dokumen' => $dokumen,
        ]);
    }
    /**
     * Store a newly created Pengajuan Beasiswa.
     */
    public function store(Request $request, string $id)
    {
        $dokumen = $this->getBeasiswaDocuments($id);

        $rules = $this->buildValidationRules($dokumen);
        $request->validate($rules);

        $mhs = $this->getAuthenticatedMahasiswa();

        if ($this->hasExistingSubmission($mhs->nim)) {
            return redirect()->route('pengajuan.create', ['id' => $id])
                ->with('error', 'Tidak Bisa Mengajukan Beasiswa Lagi.');
        }

        DB::beginTransaction();

        try {
            $pengajuanBeasiswa = $this->createPengajuanBeasiswa($mhs->nim, $id);
            $this->processDokumenUpload($request, $dokumen, $pengajuanBeasiswa->id);

            $this->sendSubmissionEmails($mhs->nim, $id);

            DB::commit();

            return redirect()->route('pengajuan.list-pengajuan', ['id' => $id])
                ->with('success', 'Pengajuan Beasiswa created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating Pengajuan Beasiswa: {$e->getMessage()}", ['exception' => $e]);
            dd($e->getMessage());
            return redirect()->route('pengajuan.create', ['id' => $id])
                ->with('error', 'Failed to create Beasiswa. Please try again.'. $e->getMessage());
        }
    }

    /**
     * Display the specified Pengajuan Beasiswa.
     */
    public function show(string $id)
    {
        $pengajuanBeasiswa = PengajuanBeasiswa::findOrFail($id);
        $dokumenPengajuan = $this->getPengajuanDokumen($id);

        $user = Auth::user();
        $mhs = $this->getAuthenticatedMahasiswa();
        $prodi = $this->getProdi($mhs->prodi_id);
        $jurusan = $this->getJurusan($prodi->jurusan_id);

        $dokumen = $this->getBeasiswaDocuments($pengajuanBeasiswa->beasiswa_id);

        return view('pages.Beasiswa.pengajuan-beasiswa', [
            'pengajuan' => $pengajuanBeasiswa,
            'dokumen_pengajuan' => $dokumenPengajuan,
            'prodi' => $prodi,
            'jurusan' => $jurusan,
            'user' => $user,
            'mhs' => $mhs,
            'dokumen' => $dokumen
        ]);
    }

    /**
     * Update the specified Pengajuan Beasiswa.
     */
    public function edit(Request $request, string $id)
    {
        $dokumen = $this->getBeasiswaDocuments(
            $this->getBeasiswaIdByPengajuan($id)
        );

        // build rules yang lebih fleksibel (nullable bukan required)
        $rules = $this->buildValidationRules($dokumen);

        try {
            $validated = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // kalau mau debug error
            return back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();

        try {
            $dokumenPengajuan = $this->getPengajuanDokumen($id);
            $this->updateDokumen($request, $dokumen, $dokumenPengajuan);

            DB::commit();

            return redirect()
                ->route('pengajuan.show', ['id' => $id])
                ->with('success', 'Documents updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating documents for Pengajuan ID: {$id}", [
                'exception' => $e,
            ]);

            return redirect()
                ->route('pengajuan.show', ['id' => $id])
                ->with('error', 'Failed to update documents. Please try again later.');
        }
    }




    public function showTracking(string $id)
    {
        // Get authenticated user ID
        $user_id = Auth::id();
        $pengajuan_beasiswa = PengajuanBeasiswa::findOrFail($id);

        $beasiswaID = $pengajuan_beasiswa->beasiswa_id;

        $dokumen = DB::select('
        SELECT syarat_dokumen.dokumen
        FROM beasiswa_syarat_dokumen
        JOIN syarat_dokumen
        ON beasiswa_syarat_dokumen.syarat_dokumen_id = syarat_dokumen.id
        WHERE beasiswa_syarat_dokumen.beasiswa_id = ?', [$beasiswaID]);

        // Check if the user is a Mahasiswa
        $userData = User::join('mahasiswa', 'users.id', '=', 'mahasiswa.user_id')
            ->select('users.email', 'mahasiswa.nim')
            ->where('users.id', $user_id)
            ->first();

        // Check if the user is a Reviewer
        $dataReviewer = Reviewer::join('users', 'reviewer.user_id', '=', 'users.id')
            ->join('role', 'role.id', '=', 'reviewer.role_id')
            ->select('reviewer.nip', 'reviewer.role_id', 'users.email as reviewer_email')
            ->where('users.id', $user_id)
            ->first();

        // Get detail data of pengajuan beasiswa
        $dataPengajuan = PengajuanBeasiswa::join('beasiswa', 'pengajuan_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->join('mahasiswa', 'pengajuan_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->join('kode_status', 'kode_status.id', '=', 'pengajuan_beasiswa.status')
            ->select(
                'beasiswa.*',
                'users.nama_depan',
                'users.nama_belakang',
                'pengajuan_beasiswa.id',
                'pengajuan_beasiswa.nim',
                'pengajuan_beasiswa.status',
                'pengajuan_beasiswa.komentar',
                'pengajuan_beasiswa.tanggal_pengajuan',
                'kode_status.isi_status',
            )
            ->where('pengajuan_beasiswa.id', $id)
            ->first();

        if (!$dataPengajuan) {
            abort(404, 'Pengajuan not found');
        }

        // Fetch dokumen pengajuan
        $dataDokumenPengajuan = PengajuanDokumen::join('pengajuan_beasiswa', 'pengajuan_beasiswa.id', '=', 'dokumen.id_pengajuan_beasiswa')
            ->select('dokumen.*')
            ->where('dokumen.id_pengajuan_beasiswa', $dataPengajuan->id)
            ->get();

        // Input dates from $dataPengajuan
        $tglAkhirBeasiswa = Carbon::parse($dataPengajuan->tanggal_berakhir);

        // Set $tglToleransiReviewer directly to $tglAkhirBeasiswa
        $tglToleransiReviewer = $tglAkhirBeasiswa;

        // Calculate remaining time
        $currentDate = Carbon::now();
        $totalSeconds = $currentDate->diffInSeconds($tglToleransiReviewer, false);

        $daysRemaining = $hoursRemaining = $minutesRemaining = $secondsRemaining = 0;

        if ($totalSeconds > 0) {
            $daysRemaining = intdiv($totalSeconds, 86400); // 1 day = 86400 seconds
            $hoursRemaining = intdiv($totalSeconds % 86400, 3600); // Remaining hours
            $minutesRemaining = intdiv($totalSeconds % 3600, 60); // Remaining minutes
            $secondsRemaining = $totalSeconds % 60; // Remaining seconds
        } else {
            // Notify the reviewer if the deadline has passed
            if ($dataReviewer && !empty($dataReviewer->reviewer_email)) {
                // Check if a notification has already been sent
                $existingNotification = DB::table('notifikasi')
                    ->where('id_pengajuan_beasiswa', $dataPengajuan->id)
                    ->where('user_id', $user_id)
                    ->where('status', 12)
                    ->exists();

                if (!$existingNotification) {
                    $data = [
                        'name' => "Reminder Review Pengajuan - " . $dataPengajuan->nim,
                        'message' => "The deadline for reviewing pengajuan by mahasiswa with NIM: {$dataPengajuan->nim} has passed. Please review it as soon as possible.",
                    ];

                    try {
                        // Insert a new notification record
                        DB::table('notifikasi')->insert([
                            'user_id' => $user_id,
                            'id_pengajuan_beasiswa' => $dataPengajuan->id,
                            'status' => 12, // "Sent"
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Send email
                        Mail::to($dataReviewer->reviewer_email)->send(new NotificationMail($data));
                    } catch (\Exception $e) {
                        Log::error('Failed to send review notification: ' . $e->getMessage());
                    }
                }
            }
        }

        // Prepare waktuSisa as an array
        $waktuSisa = [
            'days' => $daysRemaining,
            'hours' => $hoursRemaining,
            'minutes' => $minutesRemaining,
            'seconds' => $secondsRemaining,
        ];

        // Fetch all status codes
        $dataStatus = KodeStatus::all();

        return view('pages.Pengajuan.tracking-pengajuan', [
            'dataPengajuan' => $dataPengajuan,
            'dataDokumenPengajuan' => $dataDokumenPengajuan,
            'userData' => $userData,
            'dataStatus' => $dataStatus,
            'dataReviewer' => $dataReviewer,
            'waktuSisa' => $waktuSisa,
            'documents' => $dokumen
        ]);
    }

    public function progressPengajuan(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'reviewerComment' => 'nullable',
            'role_id' => 'required|integer'
        ]);

        $role_id = $validatedData['role_id'];

        $dataPengajuan = PengajuanBeasiswa::find($id);
        if (!$dataPengajuan) {
            return redirect()->route('pengajuan.tracking', ['id' => $id])
                ->with('error', 'Data Pengajuan not found.');
        }

        // Set data komentar
        $dataPengajuan->komentar = $validatedData['reviewerComment'] ?? null;

        if ($role_id == 1) {
            $reviseStatus = 3;
            $approveStatus = 4;
        } elseif ($role_id == 2) {
            $reviseStatus = 5;
            $approveStatus = 6;
        } elseif ($role_id == 3) {
            $reviseStatus = 7;
            $approveStatus = 8;
        } elseif ($role_id == 4) {
            $reviseStatus = 9;
            $approveStatus = 10;
        }

        // Update status based button input
        switch ($request->input('action')) {
            case 'reject':
                $dataPengajuan->status = 11;
                break;
            case 'revise':
                $dataPengajuan->status = $reviseStatus;
                break;
            case 'approve':
                $dataPengajuan->status = $approveStatus;
                if ($dataPengajuan->komentar) {
                    $dataPengajuan->komentar = "";
                }
                break;
            default:
                return redirect()->route('pengajuan.tracking', ['id' => $id])
                    ->with('error', 'Invalid action.');
        }

        $dataPengajuan->save();

        return redirect()->route('pengajuan.tracking', ['id' => $id])
            ->with('success', 'Status updated successfully.');
    }

    public function batalkanPengajuan(string $id)
    {
        PengajuanBeasiswa::where('id', $id)->delete();

        return redirect()->route('pengajuan.list-pengajuan')->with('msg', 'Pengajuan mu telah di batalkan');
    }

    private function getListPengajuan($user)
    {
        $mhs = Mahasiswa::where('user_id', $user->id)->first();

        if ($mhs) {
            return $this->getMahasiswaPengajuan($mhs->nim);
        }

        $reviewer = Reviewer::where('user_id', $user->id)->first();
        if ($reviewer->role_id === 2) {
            return $this->getKajurPengajuan($user->id);
        }

        return $this->getStaffPengajuan($reviewer->role_id);
    }

    private function getMahasiswaPengajuan($nim)
    {
        return PengajuanBeasiswa::join('beasiswa', 'pengajuan_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->join('mahasiswa', 'pengajuan_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->join('kode_status', 'kode_status.id', '=', 'pengajuan_beasiswa.status')
            ->select(
                'beasiswa.nama_beasiswa',
                'beasiswa.sumber',
                'users.nama_depan',
                'pengajuan_beasiswa.status',
                'pengajuan_beasiswa.tanggal_pengajuan',
                'kode_status.isi_status',
                'pengajuan_beasiswa.id as id_pengajuan'
            )
            ->where('mahasiswa.nim', $nim)
            ->get();
    }

    private function getKajurPengajuan($userId)
    {
        return PengajuanBeasiswa::join('beasiswa', 'pengajuan_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->join('mahasiswa', 'pengajuan_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
            ->join('jurusan', 'jurusan.id', '=', 'prodi.jurusan_id')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->select(
                'beasiswa.nama_beasiswa',
                'beasiswa.sumber',
                'users.nama_depan',
                'pengajuan_beasiswa.status',
                'pengajuan_beasiswa.tanggal_pengajuan',
                'pengajuan_beasiswa.id as id_pengajuan'
            )
            ->where('jurusan.kajur_id', $userId)
            ->whereIn('pengajuan_beasiswa.status', [4, 5])
            ->get();
    }

    // =================================================================
    // == FUNGSI YANG DIPERBARUI ADA DI BAWAH INI ==
    // =================================================================
    private function getStaffPengajuan($roleId)
    {
        // 1. Mulai membangun query dasar tanpa filter status
        $query = PengajuanBeasiswa::join('beasiswa', 'pengajuan_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->join('mahasiswa', 'pengajuan_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->select(
                'beasiswa.*',
                'users.nama_depan',
                'pengajuan_beasiswa.status',
                'pengajuan_beasiswa.tanggal_pengajuan',
                'pengajuan_beasiswa.id as id_pengajuan'
            );

        // 2. Tambahkan kondisi: terapkan filter status HANYA JIKA roleId BUKAN 4 (WD3)
        if ($roleId != 4) {
            $statusCode = match ($roleId) {
                1 => [1, 2, 3],       // Staff Kemahasiswaan
                3 => [6, 7],           // Koordinator Layanan Eksternal
                default => [8, 9],     // Role lain (selain 1, 3, dan 4)
            };
            $query->whereIn('pengajuan_beasiswa.status', $statusCode);
        }
        // Jika role_id adalah 4, kondisi di atas akan dilewati,
        // sehingga tidak ada filter 'whereIn' yang diterapkan.

        // 3. Eksekusi query dan kembalikan hasilnya
        return $query->get();
    }
    // =================================================================
    // == AKHIR DARI FUNGSI YANG DIPERBARUI ==
    // =================================================================

    private function getMahasiswaByUserId(int $userId)
    {
        return Mahasiswa::where('user_id', $userId)->first();
    }

    private function getProdiById(int $prodiId)
    {
        return Prodi::find($prodiId);
    }

    private function getJurusanById(int $jurusanId)
    {
        return Jurusan::find($jurusanId);
    }

    private function getDokumenByBeasiswaId(string $beasiswaId)
    {
        return DB::table('beasiswa_syarat_dokumen')
            ->join('syarat_dokumen', 'beasiswa_syarat_dokumen.syarat_dokumen_id', '=', 'syarat_dokumen.id')
            ->where('beasiswa_syarat_dokumen.beasiswa_id', $beasiswaId)
            ->select('syarat_dokumen.*') // Select all columns from the 'syarat_dokumen' table
            ->get(); // Use get() to return a collection of objects
    }

    private function getBeasiswaDocuments(string $beasiswaId)
    {
        return DB::select('SELECT syarat_dokumen.dokumen, syarat_dokumen.link_dokumen FROM beasiswa_syarat_dokumen
            JOIN syarat_dokumen ON beasiswa_syarat_dokumen.syarat_dokumen_id = syarat_dokumen.id
            WHERE beasiswa_syarat_dokumen.beasiswa_id = ?', [$beasiswaId]);
    }

    private function buildValidationRules(array $dokumen): array
    {
        $rules = [];
        foreach ($dokumen as $index => $item) {
            // ✅ nullable → artinya boleh kosong, kalau ada file baru harus valid
            $rules['file_' . ($index + 1)] = 'nullable|file|mimes:pdf,jpg,png|max:2048';
        }
        return $rules;
    }

    private function getAuthenticatedMahasiswa()
    {
        return Mahasiswa::where('user_id', Auth::id())->first();
    }

    private function hasExistingSubmission(string $nim): bool
    {
        return PengajuanBeasiswa::where('nim', $nim)->where('status', '!=', 11)->exists();
    }

    private function createPengajuanBeasiswa(string $nim, string $beasiswaId)
    {
        return PengajuanBeasiswa::create([
            'nim' => $nim,
            'beasiswa_id' => $beasiswaId,
            'tanggal_pengajuan' => now(),
            'status' => 1
        ]);
    }

    private function processDokumenUpload(Request $request, array $dokumen, string $pengajuanId)
    {

        $fileController = new FileController();

        foreach ($dokumen as $index => $item) {
            $fileKey = 'file_' . ($index + 1);
            $file = $request->file($fileKey);

            $newRequest = new Request();
            $newRequest->files->set('file', $file);
            $newRequest->merge(['path' => 'dokumen-pengajuan']);
            $fileUrl = $fileController->uploadFileLocal($newRequest);

            PengajuanDokumen::create([
                'kode_dokumen' => hash('sha256', $file->getClientOriginalName() . rand(0, 99999)),
                'nama_dokumen' => $file->getClientOriginalName(),
                'link_dokumen' => $fileUrl->getData()->url,
                'id_pengajuan_beasiswa' => $pengajuanId,
            ]);
        }
    }

    private function sendSubmissionEmails(string $nim, string $beasiswaId)
    {
        $emailController = new MailController();

        $emailController->sendMail(new Request($emailController->mahasiswaPengajuanMessage($nim, $beasiswaId)), false,false);
        $emailController->sendMail(new Request($emailController->reviewerPengajuanMessage($nim, $beasiswaId)), true,false);
    }

    private function getPengajuanDokumen(string $pengajuanId)
    {
        return PengajuanDokumen::where('id_pengajuan_beasiswa', $pengajuanId)->orderBy('id', 'asc')->get();
    }

    private function updateDokumen(Request $request, array $dokumen, $dokumenPengajuan)
    {
        $fileController = new FileController();

        foreach ($dokumenPengajuan as $index => $dokumenItem) {
            $fileKey = 'file_' . ($index + 1);

            if ($request->hasFile($fileKey)) {
                $fileController->deleteFile(new Request(['file_name' => $dokumenItem->nama_dokumen, 'path' => 'dokumen']));

                $file = $request->file($fileKey);
                $newRequest = new Request();
                $newRequest->files->set('file', $file);
                $newRequest->merge(['path' => 'dokumen-pengajuan']);
                $fileUrl = $fileController->uploadFileLocal($newRequest);

                $dokumenItem->update([
                    'nama_dokumen' => $file->getClientOriginalName(),
                    'link_dokumen' => $fileUrl->getData()->url
                ]);
            }
        }
    }

    private function getProdi(string $prodiId)
    {
        return Prodi::findOrFail($prodiId);
    }

    private function getJurusan(string $jurusanId)
    {
        return Jurusan::findOrFail($jurusanId);
    }

    private function getBeasiswaIdByPengajuan(string $pengajuanId)
    {
        return PengajuanBeasiswa::findOrFail($pengajuanId)->beasiswa_id;
    }

}
