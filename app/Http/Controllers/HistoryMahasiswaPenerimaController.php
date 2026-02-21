<?php

namespace App\Http\Controllers;

use App\Models\HistoryMahasiswaPenerima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class HistoryMahasiswaPenerimaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                $query = HistoryMahasiswaPenerima::query();
                $search = request('q');
                if ($search) {
                        $query->where(function($q) use ($search) {
                                $q->where('nim', 'like', "%$search%")
                                    ->orWhere('nama_mahasiswa', 'like', "%$search%")
                                    ->orWhere('nama_prodi', 'like', "%$search%")
                                    ->orWhere('nama_beasiswa', 'like', "%$search%")
                                    ->orWhere('tahun', 'like', "%$search%")
                                ;
                        });
                }
                $history = $query->orderBy('created_at', 'desc')->paginate(20)->appends(['q' => $search]);
                return view('pages.History.index', compact('history'));
    }

    /**
     * Show the form for importing data.
     */
    public function importForm()
    {
        return view('pages.History.import');
    }

    /**
     * Store imported data from Excel.
     */
    public function import(Request $request)
    {
        // Validate the file input
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|file|mimes:xlsx,csv,xls|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('excelFile');
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            $rows = (new FastExcel)->import($file);
            $rowsArr = is_array($rows) ? $rows : (method_exists($rows, 'all') ? $rows->all() : []);
            \Log::info('JUMLAH BARIS FASTEXCEL: ' . count($rowsArr));
            \Log::info('BARIS 1-10:', array_slice($rowsArr, 0, 10));
            \Log::info('BARIS 1749-1800:', array_slice($rowsArr, 1749, 51));
            DB::transaction(function () use ($rows, &$successCount, &$errorCount, &$errors) {
                foreach ($rows as $line) {
                    try {
                        // Map Excel columns
                        $data = [
                            'nim' => $line['NIM'] ?? $line['Nim'] ?? null,
                            'nama_mahasiswa' => $line['Nama Mahasiswa'] ?? $line['Nama mahasiswa'] ?? null,
                            'nama_prodi' => $line['Nama Prodi'] ?? $line['Program Studi'] ?? null,
                            'nama_beasiswa' => $line['Nama Beasiswa'] ?? $line['Beasiswa ADIk'] ?? $line['Nama beasiswa'] ?? null,
                            'tahun' => $line['Tahun'] ?? null,
                        ];

                        // Validate required fields
                        if (empty($data['nim']) || empty($data['nama_beasiswa'])) {
                            $errorCount++;
                            $errors[] = "Row skipped: Missing NIM or Nama Beasiswa";
                            \Log::warning('Validation failed:', $data);
                            continue;
                        }

                        // Check if already exists
                        $exists = HistoryMahasiswaPenerima::where('nim', $data['nim'])
                            ->where('nama_beasiswa', $data['nama_beasiswa'])
                            ->exists();

                        if (!$exists) {
                            HistoryMahasiswaPenerima::create($data);
                            $successCount++;
                            \Log::info('Data created successfully');
                        } else {
                            $errorCount++;
                            $errors[] = "Duplicate: NIM {$data['nim']} with beasiswa {$data['nama_beasiswa']}";
                            \Log::warning('Duplicate found');
                        }
                    } catch (\Exception $e) {
                        $errorCount++;
                        $errors[] = $e->getMessage();
                        \Log::error('Import row error: ' . $e->getMessage());
                    }
                }
            });

            $message = "Import selesai: {$successCount} data berhasil";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} data gagal/duplikat";
            }

            return redirect()->route('history-penerima.index')
                ->with('success', $message);
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            DB::transaction(function () use ($file, &$successCount, &$errorCount, &$errors) {
                (new FastExcel)->import($file, function ($line) use (&$successCount, &$errorCount, &$errors) {
                    try {
                        \Log::info('Processing row:', $line);
                        // Map Excel columns
                        $data = [
                            'nim' => $line['NIM'] ?? $line['Nim'] ?? null,
                            'nama_mahasiswa' => $line['Nama Mahasiswa'] ?? $line['Nama mahasiswa'] ?? null,
                            'nama_prodi' => $line['Nama Prodi'] ?? $line['Program Studi'] ?? null,
                            'nama_beasiswa' => $line['Nama Beasiswa'] ?? $line['Beasiswa ADIk'] ?? $line['Nama beasiswa'] ?? null,
                            'tahun' => $line['Tahun'] ?? null,
                        ];
                        dd($line, $data);
                        \Log::info('Mapped data:', $data);

                        // Validate required fields
                        if (empty($data['nim']) || empty($data['nama_beasiswa'])) {
                            $errorCount++;
                            $errors[] = "Row skipped: Missing NIM or Nama Beasiswa";
                            \Log::warning('Validation failed:', $data);
                            return;
                        }

                        // Check if already exists
                        $exists = HistoryMahasiswaPenerima::where('nim', $data['nim'])
                            ->where('nama_beasiswa', $data['nama_beasiswa'])
                            ->exists();

                        if (!$exists) {
                            HistoryMahasiswaPenerima::create($data);
                            $successCount++;
                            \Log::info('Data created successfully');
                        } else {
                            $errorCount++;
                            $errors[] = "Duplicate: NIM {$data['nim']} with beasiswa {$data['nama_beasiswa']}";
                            \Log::warning('Duplicate found');
                        }
                    } catch (\Exception $e) {
                        $errorCount++;
                        $errors[] = $e->getMessage();
                        Log::error('Import row error: ' . $e->getMessage());
                    }
                });
            });

            $message = "Import selesai: {$successCount} data berhasil";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} data gagal/duplikat";
            }

            return redirect()->route('history-penerima.index')
                ->with('success', $message);

        } catch (\Throwable $e) {
            Log::error('History Import Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Export data to Excel.
     */
    public function export()
    {
        $history = HistoryMahasiswaPenerima::orderBy('created_at', 'desc')->get();

        $data = $history->map(function ($item) {
            return [
                'NIM' => $item->nim,
                'Nama Mahasiswa' => $item->nama_mahasiswa,
                'Nama Prodi' => $item->nama_prodi,
                'Nama Beasiswa' => $item->nama_beasiswa,
                'Tahun' => $item->tahun,
                'Tanggal Input' => $item->created_at->format('Y-m-d'),
            ];
        });

        $fileName = 'history_penerima_beasiswa_' . now()->format('Ymd_His') . '.xlsx';
        return (new FastExcel($data))->download($fileName);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $history = HistoryMahasiswaPenerima::findOrFail($id);
            $history->delete();

            return redirect()->route('history-penerima.index')
                ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
