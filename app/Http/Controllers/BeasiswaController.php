<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\SyaratBeasiswa;
use App\Models\SyaratDokumen;
use App\Models\BenefitBeasiswa;
use App\Models\JenjangPendidikan;
use App\Models\Jurusan;
use App\Models\PengajuanBeasiswa;
use App\Models\PosterBeasiswa;
use App\Models\Prodi;
use App\Models\LinkBeasiswa;
use App\Models\Reviewer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class BeasiswaController extends Controller
{
    public function getBeasiswaDataBaseOnBeasiswaId(string $id){
        return Beasiswa::findOrFail($id);

    }

    public function index(Request $request)
    {
        $beasiswaUserTipe = []; // Set default value for $beasiswaUserTipe
        $penerimaBeasiswa = []; // Set default value for $penerimaBeasiswa

        if (Auth::check()) {
            // Jika pengguna sudah login
            $user = Auth::user();

            // Ambil data mahasiswa berdasarkan user_id
            $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

            // Jika ada data mahasiswa, ambil data penerima beasiswa
            $penerimaBeasiswa = $mahasiswa ? $mahasiswa->penerimaBeasiswa()->with('beasiswa')->get() : [];

            // Menentukan tipe beasiswa pengguna
            $beasiswaUserTipe = $this->mapBeasiswaUserTipe($penerimaBeasiswa);
        }

        // Query untuk mengambil data beasiswa
        $query = $this->buildBeasiswaQuery($request);
        $beasiswa = $query->leftjoin('poster_beasiswa as pb', 'pb.beasiswa_id', '=', 'beasiswa.id')->orderBy('beasiswa.created_at', 'asc')->paginate(8);

        // Ambil data jurusan
        $jurusan = Jurusan::all();

        return view('pages.Beasiswa.list-beasiswa', compact(
            'beasiswa',
            'penerimaBeasiswa',
            'beasiswaUserTipe',
            'jurusan'
        ));
    }


    public function getListBeasiswaForStaff(Request $request)
    {
        $query = $this->buildBeasiswaQuery($request);
        $beasiswa = $query->leftJoin('poster_beasiswa as pb', 'pb.beasiswa_id', '=', 'beasiswa.id')->paginate(10);

        $jurusan = Jurusan::all();

        return view('pages.Beasiswa.list-beasiswa-staff', compact('beasiswa', 'jurusan'));
    }

    public function buildBeasiswaQuery(Request $request)
    {
        $query = Beasiswa::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('nama_beasiswa', 'ilike', "%{$searchTerm}%");
        }

        if ($request->filled('jenis_beasiswa')) {
            $jenisBeasiswa = $request->input('jenis_beasiswa');
            foreach ($jenisBeasiswa as $jenis) {
                $query->orWhere('jenis_beasiswa', $jenis);
            }
        }

        if ($request->filled('tipe_beasiswa')) {
            $query->where('tipe_beasiswa', $request->input('tipe_beasiswa'));
        }

        if ($request->filled('jurusan')) {
            $jurusan = $request->input('jurusan');
            $query->whereHas('syaratBeasiswa', function ($q) use ($jurusan) {
                $q->where('syarat', 'like', "%{$jurusan}%");
            });
        }

        return $query;
    }

    public function mapBeasiswaUserTipe($penerimaBeasiswa)
    {
        return collect($penerimaBeasiswa)->map(function ($item) {
            $jenis = $item->beasiswa->jenis_beasiswa;
            $createdAt = $item->created_at;

            if ($jenis === 'full') {
                $status = 'Closed Permanently';
            } elseif ($jenis === 'half' && $createdAt->addYear() > now()) {
                $status = 'Closed';
            } else {
                $status = 'Open Again';
            }

            return [
                'id' => $item->beasiswa->id,
                'jenis' => $jenis,
                'status' => $status,
            ];
        })->toArray();
    }

    public function getDetailBeasiswaKipk($id)
    {
        $beasiswa = Beasiswa::findOrFail($id);
        $jurusan = Jurusan::all();
        return view('pages.Beasiswa.detail-beasiswa-kipk', compact('beasiswa', 'jurusan'));
    }

    public function getDetailBeasiswaEksternal($id)
    {
        // Ambil beasiswa berdasarkan ID
        $beasiswa = Beasiswa::with(['syaratBeasiswa', 'jenjangPendidikan', 'benefitBeasiswa', 'syaratDokumen', 'posterBeasiswa'])
            ->findOrFail($id);
        $jurusan = Jurusan::all();
         // Ambil data syarat, jenjang, benefit, dokumen, dan poster
        $syarat = $beasiswa->syaratBeasiswa->pluck('syarat')->toArray();
        $jenjang = $beasiswa->jenjangPendidikan->pluck('jenjang')->toArray();
        $benefit = $beasiswa->benefitBeasiswa->pluck('benefit')->toArray();
        $dokumen = $beasiswa->syaratDokumen->pluck('dokumen')->toArray();
        $poster = $beasiswa->posterBeasiswa->pluck('link_poster')->toArray();

        // Default value untuk status pengajuan dan mahasiswa
        $checkPengajuan = false;
        $mhsNIM = null;
        $isKajur = false;

        // Cek apakah pengguna sudah login
        if (Auth::check()) {
            // Jika sudah login, ambil data user
            $user = Auth::user();

            // Ambil data mahasiswa berdasarkan user_id
            $mhsNIM = Mahasiswa::where('user_id', $user->id)->first();
            
            // Cek apakah user adalah ketua jurusan
            $reviewer = Reviewer::where('user_id', $user->id)->first();
            $isKajur = $reviewer && $reviewer->role_id == 2;

            // Cek apakah mahasiswa sudah mengajukan beasiswa ini
            $checkPengajuan = $mhsNIM ? PengajuanBeasiswa::where('nim', $mhsNIM->nim)
                                                        ->where('beasiswa_id', $id)
                                                        ->where('status', '!=', 11)
                                                        ->exists() : false;
            
            // Logika baru: Jika beasiswa tidak allow_multiple, cek status_beasiswa mahasiswa
            if (!$checkPengajuan && $mhsNIM && !$beasiswa->allow_multiple) {
                // Jika mahasiswa sudah punya beasiswa (status_beasiswa = 1), tidak bisa apply
                $checkPengajuan = $mhsNIM->status_beasiswa == 1;
            }
        }

        // Return view dengan data yang sudah dipersiapkan
        return view('pages.Beasiswa.detail-beasiswa-eksternal', [
            'beasiswa' => $beasiswa,
            'id' => $id,
            'syarat' => $syarat,
            'jenjang' => $jenjang,
            'benefit' => $benefit,
            'dokumen' => $dokumen,
            'poster' => $poster,
            'isMengajukan' => $checkPengajuan,
            'isMhs' => $mhsNIM,
            'isKajur' => $isKajur
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $beasiswa = null;
        $syarat = null;
        return view('pages.Beasiswa.form-beasiswa', compact('beasiswa', 'syarat'));
    }

    /**
     * Store a newly created resource in storage.
     */

    private function validateData(Request $data)
    {
        // Validasi data menggunakan Validator::make
        if (in_array($data['tipe_beasiswa'], ['kipk', 'eksternal'])){
            $data['kuota_beasiswa'] = 1;
        }

        // Modifikasi tanggal_berakhir
        $tanggal_berakhir = Carbon::parse($data->tanggal_berakhir)->subDays(5);

        // Validasi tambahan untuk memastikan tanggal_mulai sesuai dengan tanggal_berakhir yang telah dimodifikasi
        if ($tanggal_berakhir->lte(Carbon::parse($data->tanggal_mulai))) {
            // return back()->withErrors(['tanggal_mulai' => 'Tanggal mulai harus sebelum tanggal berakhir - 5 hari.'])->withInput();
        }

        // Tambahkan tanggal_berakhir yang telah dimodifikasi ke data yang sudah divalidasi
        $data['tanggal_berakhir'] = $tanggal_berakhir;
        $validatedData = $data->validate($this->validation_rules, $this->validation_messages);
        // $validatedData = $validator->validated();
        // $validatedData['tanggal_berakhir'] = $tanggal_berakhir;

        return $validatedData;
    }
    private function validateEditData(Request $data)
    {
        // Validasi data menggunakan Validator::make
        if (in_array($data['tipe_beasiswa'], ['kipk', 'eksternal'])){
            $data['kuota_beasiswa'] = 1;
        }

        // Modifikasi tanggal_berakhir
        $tanggal_berakhir = Carbon::parse($data->tanggal_berakhir)->subDays(5);

        // Validasi tambahan untuk memastikan tanggal_mulai sesuai dengan tanggal_berakhir yang telah dimodifikasi
        if ($tanggal_berakhir->lte(Carbon::parse($data->tanggal_mulai))) {
            // return back()->withErrors(['tanggal_mulai' => 'Tanggal mulai harus sebelum tanggal berakhir - 5 hari.'])->withInput();
        }

        // Tambahkan tanggal_berakhir yang telah dimodifikasi ke data yang sudah divalidasi
        $data['tanggal_berakhir'] = $tanggal_berakhir;
        $validatedData = $data->validate($this->edit_validation_rules, $this->validation_messages);
        // $validatedData = $validator->validated();
        // $validatedData['tanggal_berakhir'] = $tanggal_berakhir;

        return $validatedData;
    }

    private function handleFileUpload(Request $request, $fieldName, $path)
    {
        $fileUrls = [];

        if ($request->hasFile($fieldName)) {
            foreach ($request->file($fieldName) as $file) {
                $newRequest = new Request();
                $newRequest->files->set('file', $file);
                $newRequest->merge(['path' => $path]);

                // Call the uploadFile method from FileController
                $fileController = new FileController();
                $uploadedFileUrl = $fileController->uploadFileLocal($newRequest);

                // Store the uploaded file URL in the array
                $fileUrls[] = $uploadedFileUrl->getData()->url ?? null;
            }
        }
        // dd($fileUrls);
        return $fileUrls;
    }

    private function storeBeasiswa(array $data)
    {
        // Simpan data beasiswa ke database dan dapatkan objek Beasiswa
        $beasiswa = Beasiswa::create([
            'nama_beasiswa' => $data['nama_beasiswa'],
            'deskripsi' => $data['deskripsi'],
            'youtube_url' => $data['youtube_url'] ?? null,
            'jenis_beasiswa' => $data['jenis_beasiswa'],
            'tipe_beasiswa' => $data['tipe_beasiswa'],
            'kuota' => $data['kuota_beasiswa'],
            'sumber' => $data['sumber_beasiswa'],
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_berakhir' => $data['tanggal_berakhir'],
            'publish' => $data['publish_beasiswa'],
            'allow_multiple' => $data['allow_multiple']
        ]);

        // Panggil fungsi storeLinkBeasiswa jika ada link dan tipe beasiswa sesuai
        if (!empty($data['link_beasiswa'])) {
            $this->storeLinkBeasiswa($data['link_beasiswa'], $beasiswa->id);
        }
            return $beasiswa;
    }

    private function storeLinkBeasiswa($link, $beasiswaId)
    {
        $link_beasiswa = LinkBeasiswa::where('beasiswa_id', $beasiswaId)->first();
        if ($link_beasiswa) $link_beasiswa->delete();
        LinkBeasiswa::updateOrCreate([
            'beasiswa_id' => $beasiswaId,
            'link_beasiswa' => $link
        ]);
    }
    private function storePoster(array $fileUrls, $beasiswaId)
    {
        foreach ($fileUrls as $url){
            PosterBeasiswa::create([
                'beasiswa_id' => $beasiswaId,
                'link_poster' => $url
            ]);
        }
    }

    private function validateURL(array $URLs)
    {
        $validatedURLs = [];
        foreach ($URLs as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $validatedURLs[] = $url;
            }
        }

        return $validatedURLs;
    }

    private function storeDokumen(array $nama_dokumen, array $dokumenUrls, $beasiswa)
    {
        if (isset($nama_dokumen)) {
            $index = 0;
            foreach ($nama_dokumen as $dokumen) {
                if (!empty($dokumen)) {
                    $existingDokumen = SyaratDokumen::where('dokumen', $dokumen)->first();

                    if (!$existingDokumen) {
                        // Cek apakah ada URL file yang cocok di indeks ini
                        if (isset($dokumenUrls[$index])) {
                            $existingDokumen = SyaratDokumen::create([
                                'dokumen' => $dokumen,
                                'link_dokumen' => $dokumenUrls[$index],
                            ]);
                        }
                    }

                    // Pastikan $existingDokumen benar-benar ada sebelum di-attach
                    if ($existingDokumen) {
                        $beasiswa->syaratDokumen()->attach($existingDokumen->id);
                    }

                    $index++; // Pindahkan increment index ke luar blok if
                }
            }
        }
    }

    private function storeAttributes($beasiswa, array $attributes, $modelClass, $relationMethod, $attributeName)
    {
        dd($beasiswa, $attributes, $modelClass, $relationMethod, $attributeName);
        if (isset($attributes)){
            foreach ($attributes as $attribute) {
                $existingAttribute = $modelClass::where($attributeName, $attribute)->first();

                if (!$existingAttribute) {
                    $existingAttribute = $modelClass::create([$attributeName => $attribute]);
                }

                $beasiswa->$relationMethod()->attach($existingAttribute->id);
            }
        }
    }
    private function storeSyaratBeasiswa($beasiswa, array $syaratData)
    {
        if (isset($syaratData)){
            foreach ($syaratData as $syarat) {
                $existingAttribute = SyaratBeasiswa::where('syarat', $syarat)->first();

                if (!$existingAttribute) {
                    $existingAttribute = SyaratBeasiswa::create(['syarat' => $syarat]);
                }

                $beasiswa->syaratBeasiswa()->attach($existingAttribute->id);
            }
        }
    }
    private function storeBenefitBeasiswa($beasiswa, array $benefitData)
    {
        if (isset($benefitData)){
            foreach ($benefitData as $benefit) {
                $existingAttribute = BenefitBeasiswa::where('benefit', $benefit)->first();

                if (!$existingAttribute) {
                    $existingAttribute = BenefitBeasiswa::create(['benefit' => $benefit]);
                }

                $beasiswa->benefitBeasiswa()->attach($existingAttribute->id);
            }
        }
    }

    private function storeJenjang(array $jenjangData, $beasiswa)
    {
        // Simpan jenjang pendidikan, jika ada
        if (isset($jenjangData)) {
            foreach ($jenjangData as $jenjang){
                JenjangPendidikan::create([
                    'beasiswa_id' => $beasiswa->id,
                    'jenjang' => $jenjang
                ]);
            }
        }
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateData($request);
        try {
            if ($validatedData instanceof RedirectResponse){
                return $validatedData;
            }
            // dd($validatedData);
            if (in_array($validatedData['tipe_beasiswa'], ['kipk', 'eksternal'])){
                $beasiswa = $this->storeBeasiswa($validatedData);
            } else {
                $dokumenUrls = $this->handleFileUpload($request, 'dokumen_file', 'dokumen');

                $beasiswa = $this->storeBeasiswa($validatedData);

                $existingposters = $request->input('poster');
                if (isset($existingposters)) {
                    $fileUrls[] = $this->validateURL($existingposters);
                }


                // $this->storeDokumen($validatedData['nama_dokumen'], $dokumenUrls, $beasiswa);
                if (isset($validatedData['nama_dokumen'])) {
                    $this->storeDokumen($validatedData['nama_dokumen'], $dokumenUrls, $beasiswa);
                }

                // Simpan syarat-syarat beasiswa
                // $this->storeAttributes($beasiswa, $validatedData['syarat_beasiswa'], 'SyaratBeasiswa', 'syaratBeasiswa', 'syarat');
                // $this->storeSyaratBeasiswa($beasiswa, $validatedData['syarat_beasiswa']);
                if (isset($validatedData['syarat_beasiswa'])) {
                    $this->storeSyaratBeasiswa($beasiswa, $validatedData['syarat_beasiswa']);
                }

                // Simpan benefit beasiswa
                // $this->storeAttributes($beasiswa, $validatedData['benefit_beasiswa'], 'BenefitBeasiswa', 'benefitBeasiswa', 'benefit');
                if (isset($validatedData['benefit_beasiswa'])) {
                    $this->storeBenefitBeasiswa($beasiswa, $validatedData['benefit_beasiswa']);
                }
                // Simpan jenjang pendidikan
                // $this->storeJenjang($validatedData['jenjang_pendidikan'], $beasiswa);
                if (isset($validatedData['jenjang_pendidikan'])) {
                    $this->storeJenjang($validatedData['jenjang_pendidikan'], $beasiswa);
                }
            }

            // upload file
            $fileUrls = $this->handleFileUpload($request, 'poster', 'poster');
            $this->storePoster($fileUrls, $beasiswa->id);

            // Log the created scholarship data
            Log::info('Beasiswa created successfully: ', [$beasiswa]);
            return redirect('/list-beasiswa-staff')->with('success', 'Beasiswa berhasil ditambahkan');
        } catch (\Throwable $th) {
            Log::error('Error creating scholarship: ', ['error' => $th->getMessage()]);
            return back()->withErrors(['msg' => 'Pembuatan Beasiswa gagal.'])->withInput($request->all());
        }
    }

    public function storea(Request $request)
    {
        // dd($request);

        // Validasi input
        // $validatedData = $request->validate($this->validation_rules, $this->validation_messages);

        //  // Modifikasi tanggal_berakhir
        // $tanggal_berakhir = Carbon::parse($request->tanggal_berakhir)->subDays(5);

        // // Validasi tambahan untuk memastikan tanggal_mulai sesuai dengan tanggal_berakhir yang telah dimodifikasi
        // if ($tanggal_berakhir->lte(Carbon::parse($request->tanggal_mulai))) {
        //     return back()->withErrors(['tanggal_mulai' => 'Tanggal mulai harus sebelum tanggal berakhir - 5 hari.'])->withInput();
        // }
        $validatedData = $this->validateData($request);

        // Handle file upload
        // $fileUrls = [];

        // if ($request->hasFile('poster')){
        //     foreach ($request->file('poster') as $file) {
        //             $newRequest = new Request();
        //             $newRequest->files->set('file', $file);
        //             $newRequest->merge(['path' => 'poster']);

        //             // Call the uploadFile method from FileController
        //             $fileController = new FileController();
        //             $uploadedFileUrl = $fileController->uploadFileLocal($newRequest);

        //             // Store the uploaded file URL in the array
        //             $fileUrls[] = $uploadedFileUrl->getData()->url ?? null;
        //     }
        // }

        $fileUrls = $this->handleFileUpload($request, 'poster', 'poster');
        $dokumenUrls = $this->handleFileUpload($request, 'dokumen_file', 'dokumen');
        // Simpan data beasiswa ke database dan dapatkan objek Beasiswa
        $beasiswa = Beasiswa::create([
            'nama_beasiswa' => $validatedData['nama_beasiswa'],
            'deskripsi' => $validatedData['deskripsi'],
            'jenis_beasiswa' => $validatedData['jenis_beasiswa'],
            'tipe_beasiswa' => $validatedData['tipe_beasiswa'],
            'kuota' => $validatedData['kuota_beasiswa'],
            'sumber' => $validatedData['sumber_beasiswa'],
            'tanggal_mulai' => $validatedData['tanggal_mulai'],
            'tanggal_berakhir' => $validatedData['tanggal_berakhir']
        ]);

        $existingposters = $request->input('poster');
        if (isset($existingposters)) {
            foreach ($existingposters as $poster) {
                if (filter_var($poster, FILTER_VALIDATE_URL)) {
                    $fileUrls[] = $poster;
                }
            }
        }

        // Simpan poster beasiswa
        foreach ($fileUrls as $url){
            PosterBeasiswa::create([
                'beasiswa_id' => $beasiswa->id,
                'link_poster' => $url
            ]);
        }

        // Simpan syarat-syarat beasiswa, jika ada
        if (isset($validatedData['syarat_beasiswa'])) {
            foreach ($validatedData['syarat_beasiswa'] as $syarat) {
                // Cari syarat dalam tabel syarat_beasiswa
                $existingSyarat = SyaratBeasiswa::where('syarat', $syarat)->first();

                // Jika syarat tidak ditemukan, tambahkan ke tabel syarat_beasiswa
                if (!$existingSyarat) {
                    $existingSyarat = SyaratBeasiswa::create(['syarat' => $syarat]);
                }

                // Hubungkan beasiswa dengan syarat (tabel pivot)
                $beasiswa->syaratBeasiswa()->attach($existingSyarat->id);
            }
        }



        // Simpan benefit beasiswa, jika ada
        if (isset($validatedData['benefit_beasiswa'])) {
            foreach ($validatedData['benefit_beasiswa'] as $benefit) {
                $existingBenefit = BenefitBeasiswa::where('benefit', $benefit)->first();

                if(!$existingBenefit){
                    $existingBenefit = BenefitBeasiswa::create(['benefit' => $benefit]);
                }

                $beasiswa->benefitBeasiswa()->attach($existingBenefit->id);
            }
        }

        if ($request->hasFile('dokumen_file')){
            foreach ($request->file('dokumen_file') as $file) {
                    $newRequest = new Request();
                    $newRequest->files->set('file', $file);
                    $newRequest->merge(['path' => 'dokumen']);

                    // Call the uploadFile method from FileController
                    $fileController = new FileController();
                    $uploadedFileUrl = $fileController->uploadFileLocal($newRequest);

                    // Store the uploaded file URL in the array
                    $dokumenUrls[] = $uploadedFileUrl->getData()->url ?? null;
            }
        }

        if (isset($validatedData['nama_dokumen'])) {
            $index = 0;
            foreach ($validatedData['nama_dokumen'] as $dokumen) {
                $existingDokumen = SyaratDokumen::where('dokumen', $dokumen)->first();


                if(!$existingDokumen){
                    $existingDokumen = SyaratDokumen::create([
                        'dokumen' => $dokumen,
                        'link_dokumen' => $dokumenUrls[$index],
                    ]);
                    $index++;
                }

                $beasiswa->syaratDokumen()->attach($existingDokumen->id);
            }
        }

        // Simpan jenjang pendidikan, jika ada
        if (isset($validatedData['jenjang_pendidikan'])) {
            foreach ($validatedData['jenjang_pendidikan'] as $jenjang){
                JenjangPendidikan::create([
                    'beasiswa_id' => $beasiswa->id,
                    'jenjang' => $jenjang
                ]);
            }
        }

        // Log the created scholarship data
        Log::info('Beasiswa created successfully: ', [$beasiswa]);

        return redirect('/list-beasiswa-staff')->with('success', 'Beasiswa berhasil ditambahkan');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil data beasiswa bersama relasi yang dibutuhkan
        $beasiswa = Beasiswa::with(['syaratBeasiswa', 'jenjangPendidikan', 'benefitBeasiswa', 'syaratDokumen', 'posterBeasiswa'])->findOrFail($id);

        // Ambil data syarat, jenjang, benefit, dokumen, dan poster
        $syarat = $beasiswa->syaratBeasiswa->pluck('syarat')->toArray();
        $jenjang = $beasiswa->jenjangPendidikan->pluck('jenjang')->toArray();
        $benefit = $beasiswa->benefitBeasiswa->pluck('benefit')->toArray();
        $dokumen = $beasiswa->syaratDokumen->pluck('dokumen')->toArray();
        $poster = $beasiswa->posterBeasiswa->pluck('link_poster')->toArray();

        // Default value untuk status pengajuan dan mahasiswa
        $checkPengajuan = false;
        $mhsNIM = null;

        // Cek apakah pengguna sudah login
        if (Auth::check()) {
            // Jika sudah login, ambil data user
            $user = Auth::user();

            // Ambil data mahasiswa berdasarkan user_id
            $mhsNIM = Mahasiswa::where('user_id', $user->id)->first();

            // Cek apakah mahasiswa sudah mengajukan beasiswa ini
            $checkPengajuan = $mhsNIM
                            ? PengajuanBeasiswa::where('nim', $mhsNIM->nim)
                                            ->where('beasiswa_id', $id)
                                            ->where('status', '!=', 11)
                                            ->exists()
                            : false;
            
            // Logika baru: Jika beasiswa tidak allow_multiple, cek status_beasiswa mahasiswa
            if (!$checkPengajuan && $mhsNIM && !$beasiswa->allow_multiple) {
                // Jika mahasiswa sudah punya beasiswa (status_beasiswa = 1), tidak bisa apply
                $checkPengajuan = $mhsNIM->status_beasiswa == 1;
            }

        }

        // Return view dengan data yang sudah dipersiapkan
        return view('pages.Beasiswa.detail-beasiswa', [
            'beasiswa' => $beasiswa,
            'id' => $id,
            'syarat' => $syarat,
            'jenjang' => $jenjang,
            'benefit' => $benefit,
            'dokumen' => $dokumen,
            'poster' => $poster,
            'isMengajukan' => $checkPengajuan,
            'isMhs' => $mhsNIM
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil data dari database berdasarkan ID
        $beasiswa = Beasiswa::with(['syaratBeasiswa', 'jenjangPendidikan', 'benefitBeasiswa', 'syaratDokumen', 'posterBeasiswa', 'linkBeasiswa'])->find($id);
        $syarat = $beasiswa->syaratBeasiswa->pluck('syarat')->toArray();
        $jenjang = $beasiswa->jenjangPendidikan->pluck('jenjang')->toArray();
        $benefit = $beasiswa->benefitBeasiswa->pluck('benefit')->toArray();
        $dokumen = $beasiswa->syaratDokumen->pluck('dokumen')->toArray();
        $link_dokumen = $beasiswa->syaratDokumen->pluck('link_dokumen')->toArray();
        $link_beasiswa = $beasiswa->linkBeasiswa;

        $poster = $beasiswa->posterBeasiswa->pluck('link_poster')->toArray();

        // Kirim data ke view
        return view('pages.Beasiswa.form-beasiswa', compact('beasiswa', 'syarat', 'jenjang', 'dokumen', 'link_dokumen', 'benefit', 'poster', 'link_beasiswa'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request);
        $validatedData = $this->validateEditData($request);
        try {
            // dd($request->all());
            if ($validatedData instanceof RedirectResponse){
                return $validatedData;
            }
            $beasiswa = Beasiswa::findOrFail($id);
            $beasiswa->fill([
                'nama_beasiswa' => $validatedData['nama_beasiswa'],
                'deskripsi' => $validatedData['deskripsi'],
                'youtube_url' => $validatedData['youtube_url'] ?? null,
                'jenis_beasiswa' => $validatedData['jenis_beasiswa'],
                'kuota' => $validatedData['kuota_beasiswa'],
                'tanggal_mulai' => $validatedData['tanggal_mulai'],
                'tanggal_berakhir' => $validatedData['tanggal_berakhir'],
                'sumber' => $validatedData['sumber_beasiswa'],
                'publish' => $validatedData['publish_beasiswa'],
                'allow_multiple' => $validatedData['allow_multiple'],
            ]);
            $beasiswa->save();

            if (in_array($beasiswa->tipe_beasiswa, ['kipk', 'eksternal'])){
                $this->storeLinkBeasiswa($validatedData['link_beasiswa'], $beasiswa->id);
            } else {
                // Simpan atau update syarat-syarat beasiswa, jika ada
                if (isset($validatedData['syarat_beasiswa'])) {
                    $beasiswa->syaratBeasiswa()->detach();
                    foreach ($validatedData['syarat_beasiswa'] as $syarat) {
                        // Cari syarat dalam tabel syarat_beasiswa
                        $existingSyarat = SyaratBeasiswa::where('syarat', $syarat)->first();

                        // Jika syarat tidak ditemukan, tambahkan ke tabel syarat_beasiswa
                        if (!$existingSyarat) {
                            $existingSyarat = SyaratBeasiswa::create(['syarat' => $syarat]);
                        }

                        // Hubungkan beasiswa dengan syarat (tabel pivot)
                        $beasiswa->syaratBeasiswa()->syncWithoutDetaching([$existingSyarat->id]);
                    }
                } else {
                    $beasiswa->syaratBeasiswa()->detach();
                }

                // Simpan atau update benefit beasiswa, jika ada
                if (isset($validatedData['benefit_beasiswa'])) {
                    $beasiswa->benefitBeasiswa()->detach();
                    foreach ($validatedData['benefit_beasiswa'] as $benefit) {
                        $existingBenefit = BenefitBeasiswa::where('benefit', $benefit)->first();

                        // Jika benefit tidak ditemukan, tambahkan ke tabel benefit_beasiswa
                        if(!$existingBenefit) {
                            $existingBenefit = BenefitBeasiswa::create(['benefit' => $benefit]);
                        }

                        // Hubungkan beasiswa dengan benefit (tabel pivot)
                        $beasiswa->benefitBeasiswa()->syncWithoutDetaching([$existingBenefit->id]);
                    }
                } else {
                    $beasiswa->benefitBeasiswa()->detach();
                }

                // Simpan atau update syarat dokumen, jika ada
                if ($request->hasFile('dokumen_file')){
                    foreach ($request->file('dokumen_file') as $file) {
                            $newRequest = new Request();
                            $newRequest->files->set('file', $file);
                            $newRequest->merge(['path' => 'dokumen']);

                            // Call the uploadFile method from FileController
                            $fileController = new FileController();
                            $uploadedFileUrl = $fileController->uploadFileLocal($newRequest);

                            // Store the uploaded file URL in the array
                            $dokumenUrls[] = $uploadedFileUrl->getData()->url ?? null;
                    }
                }

                $beasiswa->syaratDokumen()->detach();
                if (isset($validatedData['nama_dokumen'])) {
                    $index = 0;
                    foreach ($validatedData['nama_dokumen'] as $dokumen) {
                        $existingDokumen = SyaratDokumen::where('dokumen', $dokumen)->first();


                        if(!$existingDokumen){
                            $existingDokumen = SyaratDokumen::create([
                                'dokumen' => $dokumen,
                                'link_dokumen' => $dokumenUrls[$index],
                            ]);
                            $index++;
                        }
                        $beasiswa->syaratDokumen()->attach($existingDokumen->id);
                    }
                }
                // Simpan atau update jenjang pendidikan, jika ada
                if (isset($validatedData['jenjang_pendidikan'])) {
                    $beasiswa->jenjangPendidikan()->delete();
                    foreach ($validatedData['jenjang_pendidikan'] as $jenjang) {
                        // Pastikan jenjang tidak sudah ada dalam beasiswa
                        $existingJenjang = JenjangPendidikan::where('beasiswa_id', $beasiswa->id)
                                                            ->where('jenjang', $jenjang)
                                                            ->first();
                        if (!$existingJenjang) {
                            JenjangPendidikan::create([
                                'beasiswa_id' => $beasiswa->id,
                                'jenjang' => $jenjang
                            ]);
                        }
                    }
                } else {
                    $beasiswa->jenjangPendidikan()->delete();
                }

            }
            $fileUrls = [];  // Array untuk menyimpan URL file

            // dd($request->poster);
            // Memeriksa jika ada file yang diupload
            if ($request->hasFile('input_poster')) {
                foreach ($request->file('input_poster') as $file) {
                    $newRequest = new Request();
                    $newRequest->files->set('file', $file);
                    $newRequest->merge(['path' => 'poster']);

                    // Call the uploadFile method from FileController
                    $fileController = new FileController();
                    $uploadedFileUrl = $fileController->uploadFileLocal($newRequest);


                    // Store the uploaded file URL in the array
                    $fileUrls[] = $uploadedFileUrl->getData()->url ?? null;
                }
            }

            if (isset($request->poster)) {
                foreach ($request->poster as $poster) {
                    if (filter_var($poster, FILTER_VALIDATE_URL)) {
                        // dd($poster);
                        $fileUrls[] = $poster;
                    }
                }


                // Menghapus poster yang ada jika ada perubahan
                $existingPoster = PosterBeasiswa::where('beasiswa_id', $id)->get();
                if (!($fileUrls == $existingPoster)) {
                    $beasiswa->posterBeasiswa()->delete();
                }

            }
            foreach ($fileUrls as $poster) {
                // Memastikan hanya link yang valid dimasukkan
                if (filter_var($poster, FILTER_VALIDATE_URL)) {
                    $beasiswa->posterBeasiswa()->create([
                        'link_poster' => $poster,
                    ]);
                } else {
                    // Anda bisa log atau memberikan notifikasi jika ada link yang tidak valid
                    Log::warning("Invalid URL skipped: $poster");
                }
            }

            // Log the updated scholarship data
            Log::info('Beasiswa updated successfully: ', [$beasiswa]);
            return redirect()->route('beasiswa.list-beasiswa-staff')->with('success', 'Data beasiswa berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating scholarship: ', ['error' => $e->getMessage()]);

            return redirect()->route('beasiswa.list-beasiswa-staff')->with('error', 'Terjadi kesalahan saat memperbarui data beasiswa');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        // Find the item by ID
        $item = Beasiswa::findOrFail($id);

        // Delete the item
        $item->delete();

        // Redirect back with a success message
        return redirect()->route('beasiswa.list-beasiswa-staff')->with('success', 'Item deleted successfully!');

    }

    public function searchSyarat(Request $request)
    {
        $search = $request->input('query');
        $tags = SyaratBeasiswa::where('syarat', 'LIKE', "%{$search}%")->distinct()->limit(10)->get(['syarat']);

        return response()->json($tags);
    }
    public function searchDokumen(Request $request)
    {
        $search = $request->input('query');
        $tags = SyaratDokumen::where('dokumen', 'LIKE', "%{$search}%")->distinct()->limit(10)->get(['dokumen', 'link_dokumen']);

        return response()->json($tags);
    }
    public function searchBenefit(Request $request)
    {
        $search = $request->input('query');
        $tags = BenefitBeasiswa::where('benefit', 'LIKE', "%{$search}%")->distinct()->limit(10)->get(['benefit']);

        return response()->json($tags);
    }

    public function searchJenjang(Request $request)
    {
        $search = $request->input('query');
        $tags = Prodi::where('nama_prodi', 'LIKE', "%{$search}%")->distinct()->limit(10)->get(['nama_prodi']);
        $tags->prepend(['nama_prodi' => 'Semua Jenjang']);

        return response()->json($tags);
    }

     public function getBeasiswaTemplate(Request $request)
    {
        $templates = beasiswa::select('id', 'nama_beasiswa', 'deskripsi')
            ->orderBy('updated_at', 'desc')
            ->paginate(5);

        // Perpendek deskripsi
        $templates->getCollection()->transform(function ($item) {
            $item->deskripsi = Str::limit($item->deskripsi, 100, '...');
            return $item;
        });

        return response()->json([
            'data' => $templates->items(),
            'current_page' => $templates->currentPage(),
            'last_page' => $templates->lastPage(),
        ]);
    }


    public function getBeasiswa($id)
    {
        // Ambil data dari database berdasarkan ID
        $beasiswa = Beasiswa::with([
            'syaratBeasiswa',
            'jenjangPendidikan',
            'benefitBeasiswa',
            'syaratDokumen',
            'posterBeasiswa',
            'linkBeasiswa'
        ])->find($id); // Just use find($id) without 'id' and without get()

        // Cek apakah data beasiswa ditemukan
        if (!$beasiswa) {
            return response()->json(['message' => 'Beasiswa not found'], 404);
        }

        // Ambil data dari relasi dan pluck kolom yang dibutuhkan
        $syarat = $beasiswa->syaratBeasiswa->pluck('syarat')->toArray();
        $jenjang = $beasiswa->jenjangPendidikan->pluck('jenjang')->toArray();
        $benefit = $beasiswa->benefitBeasiswa->pluck('benefit')->toArray();
        $dokumen = $beasiswa->syaratDokumen->pluck('dokumen')->toArray();
        $link_dokumen = $beasiswa->syaratDokumen->pluck('link_dokumen')->toArray();
        $poster = $beasiswa->posterBeasiswa->pluck('link_poster')->toArray();
        $link_beasiswa = $beasiswa->linkBeasiswa;
        // dd($beasiswa, $syarat, $jenjang, $benefit, $dokumen, $link_dokumen, $poster);
        // Return data dalam format JSON
        return response()->json([
            'beasiswa' => $beasiswa,
            'syarat' => $syarat,
            'jenjang' => $jenjang,
            'benefit' => $benefit,
            'dokumen' => $dokumen,
            'link_dokumen' => $link_dokumen,
            'poster' => $poster,
            'link_beasiswa' => $link_beasiswa
        ]);
    }


    private $validation_messages = [
        'nama_beasiswa.required' => 'Nama beasiswa wajib diisi.',
        'nama_beasiswa.string' => 'Nama beasiswa harus berupa teks.',
        'nama_beasiswa.max' => 'Nama beasiswa tidak boleh lebih dari 255 karakter.',
        'deskripsi.required' => 'Deskripsi beasiswa tidak boleh kosong.',
        'deskripsi.string' => 'Deskripsi harus berupa teks.',
        'jenis_beasiswa.required' => 'Jenis beasiswa harus diisi.',
        'tipe_beasiswa.required' => 'Tipe beasiswa harus diisi.',
        'kuota_beasiswa.required' => 'Kuota beasiswa harus diisi.',
        'kuota_beasiswa.integer' => 'Kuota beasiswa harus berupa angka.',
        'kuota_beasiswa.min' => 'Kuota beasiswa minimal adalah 1.',
        'sumber_beasiswa.required' => 'Sumber beasiswa wajib diisi.',
        'sumber_beasiswa.string' => 'Sumber beasiswa harus berupa teks.',
        'sumber_beasiswa.max' => 'Sumber beasiswa tidak boleh lebih dari 255 karakter.',
        'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
        'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
        'tanggal_mulai.before' => 'Tanggal mulai harus sebelum tanggal berakhir.',
        'tanggal_berakhir.required' => 'Tanggal berakhir harus diisi.',
        'tanggal_berakhir.date' => 'Tanggal berakhir harus berupa tanggal yang valid.',
        'tanggal_berakhir.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
        'poster.required' => 'Poster Beasiswa wajib ada.',
        'poster.max' => 'Poster Beasiswa tidak boleh lebih dari 3.',
        'syarat_beasiswa.required_if' => 'Syarat beasiswa wajib diisi untuk beasiswa internal.',
        'nama_dokumen.required_if' => 'Nama dokumen wajib diisi untuk beasiswa internal.',
        'benefit_beasiswa.required_if' => 'Benefit beasiswa wajib diisi untuk beasiswa internal.',
        'jenjang_pendidikan.required_if' => 'Jenjang pendidikan wajib diisi untuk beasiswa internal.'
    ];

    private $validation_rules = [
        'nama_beasiswa' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'youtube_url' => 'nullable|url',
        'jenis_beasiswa' => 'required|string|in:full,half',
        'tipe_beasiswa' => 'required|string|in:kipk,internal,eksternal',
        'kuota_beasiswa' => 'required|integer|min:1',
        'sumber_beasiswa' => 'required|string|max:255',
        'tanggal_mulai' => 'required|date|before:tanggal_berakhir',
        'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
        'syarat_beasiswa' => 'required_if:tipe_beasiswa,internal|array',
        'syarat_beasiswa.*' => 'string|nullable',
        'nama_dokumen' => 'required_if:tipe_beasiswa,internal|array',
        'nama_dokumen.*' => 'nullable|string',
        'benefit_beasiswa' => 'required_if:tipe_beasiswa,internal|array',
        'benefit_beasiswa.*' => 'string|max:255|nullable',
        'jenjang_pendidikan' => 'required_if:tipe_beasiswa,internal|array',
        'jenjang_pendidikan.*' => 'string|max:100|nullable',
        'poster' => 'required|array|max:3',
        'poster.*' => 'required|file|mimes:jpeg,png,jpg',
        'link_beasiswa' => 'nullable|url',
        'publish_beasiswa' => 'required|boolean',
        'allow_multiple' => 'required|boolean'

    ];
    private $edit_validation_rules = [
        'nama_beasiswa' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'youtube_url' => 'nullable|url',
        'jenis_beasiswa' => 'required|string|in:full,half',
        'tipe_beasiswa' => 'string|in:kipk,internal,eksternal',
        'kuota_beasiswa' => 'required|integer|min:1',
        'sumber_beasiswa' => 'required|string|max:255',
        'tanggal_mulai' => 'required|date|before:tanggal_berakhir',
        'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
        'syarat_beasiswa' => 'array',
        'syarat_beasiswa.*' => 'string|nullable',
        'nama_dokumen' => 'array',
        'nama_dokumen.*' => 'nullable|string',
        'benefit_beasiswa' => 'array',
        'benefit_beasiswa.*' => 'string|max:255|nullable',
        'jenjang_pendidikan' => 'array',
        'jenjang_pendidikan.*' => 'string|max:100|nullable',
        'poster' => 'required|array|max:3',
        'poster.*' => 'required|string',
        'link_beasiswa' => 'nullable|url',
        'publish_beasiswa' => 'required|boolean',
        'allow_multiple' => 'required|boolean'

    ];

    /**
     * Cek apakah mahasiswa memiliki beasiswa aktif
     */
    private function hasActiveBeasiswa(string $nim)
    {
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

    /**
     * API: Get all beasiswa with pagination and filters
     */
    public function apiIndex(Request $request)
    {
        try {
            $query = $this->buildBeasiswaQuery($request);
            
            // Include relationships
            $query->with([
                'syaratBeasiswa',
                'benefitBeasiswa',
                'posterBeasiswa',
                'linkBeasiswa',
                'syaratDokumen'
            ]);

            // Paginate results (default 15 per page)
            $perPage = $request->input('per_page', 15);
            $beasiswa = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data beasiswa berhasil diambil',
                'data' => $beasiswa
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data beasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get single beasiswa by ID
     */
    public function apiShow($id)
    {
        try {
            $beasiswa = Beasiswa::with([
                'syaratBeasiswa',
                'benefitBeasiswa',
                'posterBeasiswa',
                'linkBeasiswa',
                'syaratDokumen'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail beasiswa berhasil diambil',
                'data' => $beasiswa
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Beasiswa tidak ditemukan',
                'error' => 'Beasiswa dengan ID tersebut tidak ada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail beasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
