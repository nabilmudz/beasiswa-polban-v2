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
use Rap2hpoutre\FastExcel\FastExcel;

class PengajuanBeasiswaController extends Controller
{
    public function listPengajuanStaff(Request $request)
    {
        $user = Auth::user();
        
        // Debug: Log parameter yang diterima
        Log::info('Filter Parameters:', [
            'nama_beasiswa' => $request->nama_beasiswa,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'tanggal_dari' => $request->tanggal_dari,
            'tanggal_sampai' => $request->tanggal_sampai,
            'all_params' => $request->all()
        ]);
        
        $listPengajuan = $this->getListPengajuan($user, $request);
        $namaBeasiswa = Beasiswa::pluck('nama_beasiswa');

        return view('pages.Beasiswa.list-pengaju-beasiswa', compact('listPengajuan','namaBeasiswa'));
    }


    public function create(string $id)
    {
        $user = Auth::user();
        
        // Cek apakah user adalah ketua jurusan
        $reviewer = Reviewer::where('user_id', $user->id)->first();
        $isKajur = $reviewer && $reviewer->role_id == 2;
        
        if ($isKajur) {
            // Jika ketua jurusan, arahkan ke halaman pemilihan mahasiswa
            return $this->createForKajur($id);
        }
        
        // Jika mahasiswa, lanjutkan seperti biasa
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
            'isKajur' => false,
        ]);
    }
    
    /**
     * Form untuk ketua jurusan memilih mahasiswa
     */
    public function createForKajur(string $id)
    {
        $user = Auth::user();
        $reviewer = Reviewer::where('user_id', $user->id)->first();
        
        if (!$reviewer || $reviewer->role_id != 2) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        // Ambil jurusan dari kajur
        $jurusan = Jurusan::where('kajur_id', $user->id)->first();
        
        if (!$jurusan) {
            return redirect()->back()->with('error', 'Jurusan tidak ditemukan.');
        }
        
        // Ambil semua mahasiswa di jurusan tersebut dengan eager loading
        $mahasiswaList = Mahasiswa::with(['user', 'prodi'])
            ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->where('prodi.jurusan_id', $jurusan->id)
            ->select('mahasiswa.*')
            ->get();
        
        $dokumen = $this->getDokumenByBeasiswaId($id);
        
        return view('pages.Beasiswa.pengajuan-beasiswa-kajur', [
            'user' => $user,
            'beasiswa_id' => $id,
            'jurusan' => $jurusan,
            'mahasiswaList' => $mahasiswaList,
            'dokumen' => $dokumen,
        ]);
    }
    
    /**
     * Store pengajuan beasiswa oleh ketua jurusan untuk mahasiswa
     */
    public function storeForKajur(Request $request, string $id)
    {
        $user = Auth::user();
        $reviewer = Reviewer::where('user_id', $user->id)->first();
        
        if (!$reviewer || $reviewer->role_id != 2) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }
        
        // Validasi NIM yang dipilih
        $request->validate([
            'nim' => 'required|exists:mahasiswa,nim'
        ]);
        
        $nim = $request->input('nim');
        $dokumen = $this->getBeasiswaDocuments($id);
        
        $rules = $this->buildValidationRules($dokumen);
        $request->validate($rules);
        
        // Cek apakah mahasiswa tersebut dari jurusan yang benar
        $jurusan = Jurusan::where('kajur_id', $user->id)->first();
        $mahasiswa = Mahasiswa::join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->where('mahasiswa.nim', $nim)
            ->where('prodi.jurusan_id', $jurusan->id)
            ->select('mahasiswa.*')
            ->first();
            
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Mahasiswa tidak ditemukan di jurusan Anda.');
        }
        
        // Cek apakah mahasiswa sudah punya beasiswa aktif
        $beasiswaController = new BeasiswaController();
        $beasiswa = $beasiswaController->getBeasiswaDataBaseOnBeasiswaId($id);
        
        if (!$beasiswa->allow_multiple && $mahasiswa->status_beasiswa == 1) {
            return redirect()->back()
                ->with('error', 'Mahasiswa ini sudah memiliki beasiswa aktif (' . $mahasiswa->nama_beasiswa_saat_ini . '). Beasiswa ini tidak mengizinkan beasiswa ganda.');
        }
        
        if ($this->hasExistingSubmission($nim)) {
            return redirect()->back()
                ->with('error', 'Mahasiswa ini sudah memiliki pengajuan beasiswa aktif.');
        }
        
        DB::beginTransaction();
        
        try {
            $pengajuanBeasiswa = $this->createPengajuanBeasiswa($nim, $id);
            
            Log::info("Created PengajuanBeasiswa by Kajur", [
                'id' => $pengajuanBeasiswa->id,
                'nim' => $pengajuanBeasiswa->nim,
                'beasiswa_id' => $pengajuanBeasiswa->beasiswa_id,
                'user_id_pengaju' => $pengajuanBeasiswa->user_id_pengaju
            ]);
            
            if (!$pengajuanBeasiswa->id) {
                throw new \Exception("Failed to generate Pengajuan ID");
            }
            
            $this->processDokumenUpload($request, $dokumen, $pengajuanBeasiswa->id);
            $this->sendSubmissionEmails($nim, $id);
            
            DB::commit();
            
            return redirect()->route('pengajuan.list-pengajuan', ['id' => $id])
                ->with('success', 'Pengajuan Beasiswa untuk mahasiswa berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating Pengajuan Beasiswa by Kajur: {$e->getMessage()}", ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'Gagal membuat pengajuan beasiswa. Silakan coba lagi. ' . $e->getMessage());
        }
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

        // Cek apakah mahasiswa sudah punya beasiswa aktif (berdasarkan status_beasiswa)
        $beasiswaController = new BeasiswaController();
        $beasiswa = $beasiswaController->getBeasiswaDataBaseOnBeasiswaId($id);
        
        // Logika baru: Jika beasiswa tidak mengizinkan multiple dan mahasiswa sudah punya beasiswa
        if (!$beasiswa->allow_multiple && $mhs->status_beasiswa == 1) {
            return redirect()->route('pengajuan.create', ['id' => $id])
                ->with('error', 'Anda sudah memiliki beasiswa aktif (' . $mhs->nama_beasiswa_saat_ini . '). Beasiswa ini tidak mengizinkan beasiswa ganda.');
        }

        if ($this->hasExistingSubmission($mhs->nim)) {
            return redirect()->route('pengajuan.create', ['id' => $id])
                ->with('error', 'Tidak Bisa Mengajukan Beasiswa Lagi.');
        }

        DB::beginTransaction();

        try {
            $pengajuanBeasiswa = $this->createPengajuanBeasiswa($mhs->nim, $id);
            
            Log::info("Created PengajuanBeasiswa", [
                'id' => $pengajuanBeasiswa->id,
                'nim' => $pengajuanBeasiswa->nim,
                'beasiswa_id' => $pengajuanBeasiswa->beasiswa_id
            ]);
            
            if (!$pengajuanBeasiswa->id) {
                throw new \Exception("Failed to generate Pengajuan ID");
            }
            
            $this->processDokumenUpload($request, $dokumen, $pengajuanBeasiswa->id);

            $this->sendSubmissionEmails($mhs->nim, $id);

            DB::commit();

            return redirect()->route('pengajuan.list-pengajuan', ['id' => $id])
                ->with('success', 'Pengajuan Beasiswa created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating Pengajuan Beasiswa: {$e->getMessage()}", ['exception' => $e]);
            return redirect()->route('pengajuan.create', ['id' => $id])
                ->with('error', 'Failed to create Beasiswa. Please try again. ' . $e->getMessage());
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
            $reviseStatus = 5;
            $approveStatus = 6;
        } elseif ($role_id == 2) {
            $reviseStatus = 3;
            $approveStatus = 4;
        } elseif ($role_id == 4) {
            $reviseStatus = 7;
            $approveStatus = 8;
        }

        // Update status based button input
        switch ($request->input('action')) {
            case 'reject':
                $dataPengajuan->status = 9;
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

    private function getListPengajuan($user, $request = null)
    {
        $mhs = Mahasiswa::where('user_id', $user->id)->first();

        if ($mhs) {
            Log::info('User is Mahasiswa, calling getMahasiswaPengajuan');
            return $this->getMahasiswaPengajuan($mhs->nim, $request);
        }

        $reviewer = Reviewer::where('user_id', $user->id)->first();
        if ($reviewer->role_id === 2) {
            Log::info('User is Kajur, calling getKajurPengajuan');
            return $this->getKajurPengajuan($user->id, $request);
        }

        Log::info('User is Staff/Admin, calling getStaffPengajuan with role_id: ' . $reviewer->role_id);
        return $this->getStaffPengajuan($reviewer->role_id, $request);
    }

    private function getMahasiswaPengajuan($nim, $request = null)
    {
        $query = PengajuanBeasiswa::join('beasiswa', 'pengajuan_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->join('mahasiswa', 'pengajuan_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->join('kode_status', 'kode_status.id', '=', 'pengajuan_beasiswa.status')
            ->select(
                'beasiswa.nama_beasiswa',
                'beasiswa.sumber',
                'users.nama_depan',
                'users.nama_belakang',
                'pengajuan_beasiswa.status',
                'pengajuan_beasiswa.tanggal_pengajuan',
                'kode_status.isi_status',
                'pengajuan_beasiswa.id as id_pengajuan'
            )
            ->where('mahasiswa.nim', $nim);

        // Apply filters if request exists
        if ($request) {
            if ($request->filled('nama_beasiswa')) {
                $query->where('beasiswa.nama_beasiswa', $request->nama_beasiswa);
            }
            if ($request->filled('tanggal_pengajuan')) {
                $query->whereDate('pengajuan_beasiswa.tanggal_pengajuan', $request->tanggal_pengajuan);
            }
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('pengajuan_beasiswa.tanggal_pengajuan', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('pengajuan_beasiswa.tanggal_pengajuan', '<=', $request->tanggal_sampai);
            }
            // Filter berdasarkan status
            if ($request->filled('status')) {
                $statusFilter = $request->status;
                if ($statusFilter === 'diproses') {
                    $query->whereIn('pengajuan_beasiswa.status', [1, 2, 3, 4, 5, 6, 7]);
                } elseif ($statusFilter === 'diterima') {
                    $query->where('pengajuan_beasiswa.status', 8);
                } elseif ($statusFilter === 'ditolak') {
                    $query->where('pengajuan_beasiswa.status', 9);
                }
            }
        }

        return $query->orderBy('pengajuan_beasiswa.tanggal_pengajuan', 'desc')->get();
    }

    private function getKajurPengajuan($userId, $request = null)
    {
        $query = PengajuanBeasiswa::join('beasiswa', 'pengajuan_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->join('mahasiswa', 'pengajuan_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('prodi', 'prodi.id', '=', 'mahasiswa.prodi_id')
            ->join('jurusan', 'jurusan.id', '=', 'prodi.jurusan_id')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->select(
                'beasiswa.nama_beasiswa',
                'beasiswa.sumber',
                'users.nama_depan',
                'users.nama_belakang',
                'pengajuan_beasiswa.status',
                'pengajuan_beasiswa.tanggal_pengajuan',
                'pengajuan_beasiswa.id as id_pengajuan'
            )
            ->where('jurusan.kajur_id', $userId)
            ->whereIn('pengajuan_beasiswa.status', [1, 2, 3]);

        // Apply filters if request exists
        if ($request) {
            if ($request->filled('nama_beasiswa')) {
                $query->where('beasiswa.nama_beasiswa', $request->nama_beasiswa);
            }
            if ($request->filled('tanggal_pengajuan')) {
                $query->whereDate('pengajuan_beasiswa.tanggal_pengajuan', $request->tanggal_pengajuan);
            }
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('pengajuan_beasiswa.tanggal_pengajuan', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('pengajuan_beasiswa.tanggal_pengajuan', '<=', $request->tanggal_sampai);
            }
            // Filter berdasarkan status
            if ($request->filled('status')) {
                $statusFilter = $request->status;
                if ($statusFilter === 'diproses') {
                    $query->whereIn('pengajuan_beasiswa.status', [1, 2, 3, 4, 5, 6, 7]);
                } elseif ($statusFilter === 'diterima') {
                    $query->where('pengajuan_beasiswa.status', 8);
                } elseif ($statusFilter === 'ditolak') {
                    $query->where('pengajuan_beasiswa.status', 9);
                }
            }
        }

        return $query->orderBy('pengajuan_beasiswa.tanggal_pengajuan', 'desc')->get();
    }

    // =================================================================
    // == FUNGSI YANG DIPERBARUI ADA DI BAWAH INI ==
    // =================================================================
    private function getStaffPengajuan($roleId, $request = null)
    {
        // 1. Mulai membangun query dasar tanpa filter status
        $query = PengajuanBeasiswa::join('beasiswa', 'pengajuan_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->join('mahasiswa', 'pengajuan_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->select(
                'beasiswa.nama_beasiswa',
                'beasiswa.sumber',
                'users.nama_depan',
                'users.nama_belakang',
                'pengajuan_beasiswa.status',
                'pengajuan_beasiswa.tanggal_pengajuan',
                'pengajuan_beasiswa.id as id_pengajuan'
            );

        // 2. Tidak ada filter status - Admin dan WD3 melihat semua pengajuan
        // Role_id 1 (Staff Kemahasiswaan/Admin) dan role_id 4 (WD3) melihat semua status

        // 3. Terapkan filter lanjutan jika ada request
        if ($request) {
            // Filter berdasarkan nama beasiswa
            if ($request->filled('nama_beasiswa')) {
                $namaBeasiswaFilter = $request->nama_beasiswa;
                Log::info('Applying beasiswa filter: ' . $namaBeasiswaFilter);
                $query->where('beasiswa.nama_beasiswa', $namaBeasiswaFilter);
            }

            // Filter berdasarkan tanggal pengajuan spesifik
            if ($request->filled('tanggal_pengajuan')) {
                Log::info('Applying date filter: ' . $request->tanggal_pengajuan);
                $query->whereDate('pengajuan_beasiswa.tanggal_pengajuan', $request->tanggal_pengajuan);
            }

            // Filter berdasarkan rentang tanggal
            if ($request->filled('tanggal_dari')) {
                Log::info('Applying date from filter: ' . $request->tanggal_dari);
                $query->whereDate('pengajuan_beasiswa.tanggal_pengajuan', '>=', $request->tanggal_dari);
            }

            if ($request->filled('tanggal_sampai')) {
                Log::info('Applying date to filter: ' . $request->tanggal_sampai);
                $query->whereDate('pengajuan_beasiswa.tanggal_pengajuan', '<=', $request->tanggal_sampai);
            }
            
            // Filter berdasarkan status
            if ($request->filled('status')) {
                $statusFilter = $request->status;
                Log::info('Applying status filter: ' . $statusFilter);
                if ($statusFilter === 'diproses') {
                    $query->whereIn('pengajuan_beasiswa.status', [1, 2, 3, 4, 5, 6, 7]);
                } elseif ($statusFilter === 'diterima') {
                    $query->where('pengajuan_beasiswa.status', 8);
                } elseif ($statusFilter === 'ditolak') {
                    $query->where('pengajuan_beasiswa.status', 9);
                }
            }
        }

        // 4. Eksekusi query dan kembalikan hasilnya
        $result = $query->orderBy('pengajuan_beasiswa.tanggal_pengajuan', 'desc')->get();
        
        Log::info('Query result count: ' . $result->count());
        if ($result->count() > 0) {
            Log::info('First result: ', $result->first()->toArray());
        }
        
        return $result;
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
        return PengajuanBeasiswa::where('nim', $nim)->where('status', '!=', 9)->exists();
    }

    private function createPengajuanBeasiswa(string $nim, string $beasiswaId)
    {
        return PengajuanBeasiswa::create([
            'nim' => $nim,
            'beasiswa_id' => $beasiswaId,
            'user_id_pengaju' => Auth::id(), // Menyimpan siapa yang mengajukan
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

            // Skip jika file tidak ada
            if (!$file) {
                Log::info("Skipping file upload for {$fileKey} - no file provided");
                continue;
            }

            Log::info("Processing file upload for {$fileKey}", [
                'filename' => $file->getClientOriginalName(),
                'pengajuan_id' => $pengajuanId
            ]);

            try {
                $newRequest = new Request();
                $newRequest->files->set('file', $file);
                $newRequest->merge(['path' => 'dokumen-pengajuan']);
                $fileUrlResponse = $fileController->uploadFileLocal($newRequest);
                
                // Get URL from JSON response
                $fileUrlData = json_decode($fileUrlResponse->getContent(), true);
                $fileUrl = $fileUrlData['url'] ?? null;
                
                if (!$fileUrl) {
                    throw new \Exception("Failed to get file URL for {$fileKey}");
                }

                PengajuanDokumen::create([
                    'kode_dokumen' => hash('sha256', $file->getClientOriginalName() . rand(0, 99999)),
                    'nama_dokumen' => $file->getClientOriginalName(),
                    'link_dokumen' => $fileUrl,
                    'id_pengajuan_beasiswa' => $pengajuanId,
                ]);
                
                Log::info("Successfully uploaded document for {$fileKey}");
            } catch (\Exception $e) {
                Log::error("Error uploading file {$fileKey}: " . $e->getMessage());
                throw $e;
            }
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
        return PengajuanDokumen::where('id_pengajuan_beasiswa', $pengajuanId)->orderBy('created_at', 'asc')->get();
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
                $fileUrlResponse = $fileController->uploadFileLocal($newRequest);
                
                // Get URL from JSON response
                $fileUrlData = json_decode($fileUrlResponse->getContent(), true);
                $fileUrl = $fileUrlData['url'] ?? null;

                $dokumenItem->update([
                    'nama_dokumen' => $file->getClientOriginalName(),
                    'link_dokumen' => $fileUrl
                ]);
            }
        }
    }

    private function getProdi(string $prodiId)
    {
        return Prodi::findOrFail($prodiId);
    }

    private function hasActiveBeasiswa(string $nim)
    {
        // Cek apakah mahasiswa punya penerima beasiswa yang aktif
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        if (!$mahasiswa) {
            return false;
        }

        // Cek di tabel penerima_beasiswa
        $penerimaBeasiswa = $mahasiswa->penerimaBeasiswa()->with('beasiswa')->get();
        
        foreach ($penerimaBeasiswa as $penerima) {
            $beasiswa = $penerima->beasiswa;
            
            // Cek jika beasiswa masih aktif (tanggal berakhir >= hari ini)
            if ($beasiswa && $beasiswa->tanggal_berakhir >= now()) {
                return true;
            }
        }

        return false;
    }

    private function getJurusan(string $jurusanId)
    {
        return Jurusan::findOrFail($jurusanId);
    }

    private function getBeasiswaIdByPengajuan(string $pengajuanId)
    {
        return PengajuanBeasiswa::findOrFail($pengajuanId)->beasiswa_id;
    }

    public function exportPengajuan(Request $request)
    {
        $user = Auth::user();
        
        // Get filtered data using the same method as list view
        $listPengajuan = $this->getListPengajuan($user, $request);
        
        // Check if there's data to export
        if ($listPengajuan->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk di-export.');
        }
        
        // Map data to Excel format
        $exportData = $listPengajuan->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'NIM' => $item->nim ?? '-',
                'Nama Pengaju' => ($item->nama_depan ?? '') . ' ' . ($item->nama_belakang ?? ''),
                'Nama Beasiswa' => $item->nama_beasiswa ?? '-',
                'Penyelenggara' => $item->sumber ?? '-',
                'Tanggal Pengajuan' => $item->tanggal_pengajuan ? Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') : '-',
                'Status' => $item->isi_status ?? $this->getStatusText($item->status),
            ];
        });
        
        // Generate filename with timestamp
        $filename = 'Daftar_Pengajuan_Beasiswa_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
        
        // Export to Excel
        return (new FastExcel($exportData))->download($filename);
    }
    
    private function getStatusText($statusCode)
    {
        $statusMap = [
            1 => 'Diajukan',
            2 => 'Diproses oleh Ketua Jurusan',
            3 => 'Direvisi pada Ketua Jurusan',
            4 => 'Diproses oleh Staff Kemahasiswaan',
            5 => 'Direvisi pada Pengecekan Staff Kemahasiswaan',
            6 => 'Diproses oleh Wakil Direktur 3',
            7 => 'Direvisi pada Wakil Direktur 3',
            8 => 'Diterima',
            9 => 'Ditolak',
        ];
        
        return $statusMap[$statusCode] ?? 'Status Tidak Diketahui';
    }

}
