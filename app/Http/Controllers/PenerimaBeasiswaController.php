<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BeasiswaController;
use App\Models\Beasiswa;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\PenerimaBeasiswa;
use App\Models\PengajuanBeasiswa;
use App\Models\Reviewer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class PenerimaBeasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $beasiswaUserTipe = []; // Set default value for $beasiswaUserTipe
        $penerimaBeasiswa = []; // Set default value for $penerimaBeasiswa

        $beasiswaController = new BeasiswaController();

        if (Auth::check()) {
            // Jika pengguna sudah login
            $user = Auth::user();

            $isStaff = Reviewer::where('user_id', $user->id)
                    ->where('role_id', 1)
                    ->exists();

            // Ambil data mahasiswa berdasarkan user_id
            $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

            // Jika ada data mahasiswa, ambil data penerima beasiswa
            $penerimaBeasiswa = $mahasiswa ? $mahasiswa->penerimaBeasiswa()->with('beasiswa')->get() : [];

            // Menentukan tipe beasiswa pengguna
            $beasiswaUserTipe =   $beasiswaController->mapBeasiswaUserTipe($penerimaBeasiswa);
        }

        $query = $beasiswaController->buildBeasiswaQuery($request);

        // Jalankan query dan paginasi hasilnya
        $beasiswa = $query->leftjoin('poster_beasiswa as pb', 'pb.beasiswa_id', '=', 'beasiswa.id')
            ->paginate(8);

        // Data pengguna untuk view
        $jurusan = Jurusan::all();

        // Kirim data ke view
        return view('pages.Beasiswa.list-pengumumanBeasiswa', compact(
            'beasiswa',
            'penerimaBeasiswa',
            'beasiswaUserTipe',
            'jurusan',
            'isStaff'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusan = Jurusan::all();
        return view('pages.Beasiswa.import-data-beasiswa', compact('jurusan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the file input
        $this->validateRequest($request);

        try {
            $file = $request->file('excelFile');
            // dd($file);
            DB::transaction(function () use ($file) {
                $this->importBeasiswaData($file);
            });
            return redirect()->route('pengumuman-beasiswa.index')
                ->with('success', 'Beasiswa data imported successfully.');
        } catch (\Throwable $e) {
            dd($e->getMessage());
            Log::error('Beasiswa Import Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->route('beasiswa.import-data-beasiswa')
                ->with('error', 'Failed to import beasiswa data.' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $penerima_beasiswa = PenerimaBeasiswa::join('mahasiswa', 'penerima_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->join('jurusan', 'prodi.jurusan_id', '=', 'jurusan.id')
            ->where('beasiswa_id', '=', $id)
            ->get();

        if ($penerima_beasiswa->isEmpty()) {
            // Fetch the beasiswa record
            $beasiswa = Beasiswa::where('id', '=', $id)->first();
            if ($beasiswa) {
                $penerima_beasiswa = DB::table('history_mahasiswa_penerima')
                    ->where('nama_beasiswa', '=', $beasiswa->nama_beasiswa)
                    ->get();
            }
        }

        $user = Auth::user();
        $reviewer = Reviewer::where('user_id', $user->id)->first();
        $beasiswa = Beasiswa::findOrFail($id);

        return view('pages.Beasiswa.pengumuman-beasiswa', compact('penerima_beasiswa', 'beasiswa', 'reviewer'));
    }


    public function exportPenerimaBeasiswaInExcel(string $id)
    {
        // Fetch the scholarship recipients with related data
        $penerima_beasiswa = PenerimaBeasiswa::join('mahasiswa', 'penerima_beasiswa.nim', '=', 'mahasiswa.nim')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->join('jurusan', 'prodi.jurusan_id', '=', 'jurusan.id')
            ->join('beasiswa', 'penerima_beasiswa.beasiswa_id', '=', 'beasiswa.id')
            ->where('beasiswa_id', '=', $id)
            ->select(
                'penerima_beasiswa.nim',
                'users.nama_depan',
                'users.nama_belakang',
                'jurusan.nama_jurusan',
                'prodi.nama_prodi',
                'beasiswa.nama_beasiswa',
                'penerima_beasiswa.created_at' // Fixed typo
            )
            ->get();

        if ($penerima_beasiswa->isEmpty()) {
            // Fetch the beasiswa record
            $beasiswa = Beasiswa::where('id', '=', $id)->first();
            if ($beasiswa) {
                $penerima_beasiswa = DB::table('history_mahasiswa_penerima')
                    ->where('nama_beasiswa', '=', $beasiswa->nama_beasiswa)
                    ->get();
            }
        }

        // Check if there's data to export
        if ($penerima_beasiswa->isEmpty()) {
            return back()->with('error', 'No data found to export.');
        }

        // Map data to a suitable format for FastExcel
        $list = $penerima_beasiswa->map(function ($item) {
            $createdAt = Carbon::parse($item->created_at);
            return [
                'NIM' => $item->nim,
                'Nama' => isset($item->nama_depan)
                            ? $item->nama_depan
                            : ($item->nama_mahasiswa . ' ' . (isset($item->nama_belakang) ? $item->nama_belakang : '')),
                'Jurusan' => isset($item->nama_jurusan),
                'Prodi' => $item->nama_prodi,
                'Beasiswa' => $item->nama_beasiswa,
                'Tanggal Diterima' => $createdAt->format('Y-m-d')
            ];
        });


        $beasiswaName = $penerima_beasiswa->first()->nama_beasiswa ?? 'default_beasiswa';
        $fileName = 'penerima_beasiswa_' . $beasiswaName . now()->format('Ymd_His') . '.xlsx';

        // Export data using FastExcel
        return (new FastExcel($list))->download($fileName);
    }

    /**
     * Validate the incoming request for file upload.
     */
    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|file|mimes:xlsx,csv,xls|max:2048',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    }

    /**
     * Import beasiswa data from the uploaded file.
     */
    private function importBeasiswaData($file)
    {
        (new FastExcel)->import($file, function ($line) {
            $data = $this->validateRow($line);
            $beasiswa = $this->getBeasiswa($data['Nama Beasiswa']);
            $this->handlePenerimaBeasiswa($data, $beasiswa);
            $this->storeToViewIfValid($data);
        });
    }

    /**
     * Validate each row of the uploaded file.
     */
    private function validateRow(array $line): array
    {
        return Validator::make($line, [
            'NIM' => 'required|integer',
            'Nama Beasiswa' => 'required|string|exists:beasiswa,nama_beasiswa',
            'Tanggal Diterima' => 'required|date',
            'Nama Mahasiswa' => 'required|string', // Validation for Nama Mahasiswa (must be a string)
            'Nama Prodi' => 'required|string', // Validation for Nama Prodi (must be a string)
        ])->validate();
    }
    /**
     * Retrieve the beasiswa record based on the name.
     */
    private function getBeasiswa(string $beasiswaName)
    {
        $beasiswa = Beasiswa::where('nama_beasiswa', $beasiswaName)->first();

        if (!$beasiswa) {
            throw new \Exception("Beasiswa with name {$beasiswaName} not found.");
        }

        return $beasiswa;
    }

    /**
     * Handle logic for storing or updating the penerima beasiswa data.
     */
    private function handlePenerimaBeasiswa(array $data, $beasiswa)
    {
        $penerimaBeasiswa = PenerimaBeasiswa::where('nim', $data['NIM'])->first();

        if (!$penerimaBeasiswa) {
            $this->storeNewPenerimaBeasiswa($data, $beasiswa);
        } else {
            $this->handleExistingPenerimaBeasiswa($penerimaBeasiswa, $beasiswa, $data);
        }
    }

    /**
     * Store new penerima beasiswa record.
     */
    private function storeNewPenerimaBeasiswa(array $data, $beasiswa)
    {
        if (!in_array($beasiswa->tipe_beasiswa, ['eksternal', 'kipk'])) {
            $isPengajuanExists = PengajuanBeasiswa::where('nim', $data['NIM'])
                ->where('beasiswa_id', $beasiswa->id)
                ->exists();

            if ($isPengajuanExists) {
                PenerimaBeasiswa::create([
                    'nim' => $data['NIM'],
                    'beasiswa_id' => $beasiswa->id,
                ]);
            }
        } else {
            PenerimaBeasiswa::create([
                'nim' => $data['NIM'],
                'beasiswa_id' => $beasiswa->id,
            ]);
        }
    }

    /**
     * Handle existing penerima beasiswa logic.
     */
    private function handleExistingPenerimaBeasiswa($penerimaBeasiswa, $beasiswa, array $data)
    {
        if ($beasiswa->jenis_beasiswa === 'half') {
            $oneYearAgo = now()->subYear();

            if ($penerimaBeasiswa->created_at <= $oneYearAgo) {
                PenerimaBeasiswa::create([
                    'nim' => $data['NIM'],
                    'beasiswa_id' => $beasiswa->id,
                ]);
            }
        }
    }

    /**
     * Store data to the view if tanggal_diterima is before 2025.
     */
    private function storeToViewIfValid(array $data)
    {
        $tanggalDiterima = \Carbon\Carbon::parse($data['Tanggal Diterima']);

        if ($tanggalDiterima->year < 2025) {
            DB::table('history_mahasiswa_penerima')->insert([
                'nim' => $data['NIM'],
                'nama_mahasiswa' => $data['Nama Mahasiswa'], // Optional if 'nama_mahasiswa' exists
                'nama_prodi' => $data['Nama Prodi'],         // Optional if 'nama_prodi' exists
                'nama_beasiswa' => $data['Nama Beasiswa'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
