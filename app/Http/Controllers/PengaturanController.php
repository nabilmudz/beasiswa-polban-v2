<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Reviewer;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;


class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // Ambil data user yang sedang login

        // Data umum
        $user_id = $user->id;
        $user_img = $user->foto;
        $email = $user->email;
        $phone = $user->phone;
        $jk = $user->jenis_kelamin;

        // Nama pengguna
        $nama_depan = $user->nama_depan;
        $nama_belakang = $user->nama_belakang;

        // Default data untuk NIM, NIP, dan no_hp
        $nim = null;
        $nip = null;
        $no_hp = $phone;

        // Cek apakah user adalah mahasiswa
        if ($user->mahasiswa) {
            $nim = $user->mahasiswa->nim;
            $no_hp = $user->mahasiswa->no_hp ?: $phone; // Gunakan no_hp mahasiswa jika tersedia
        }

        // Cek apakah user adalah reviewer
        if ($user->reviewer) {
            $nip = $user->reviewer->nip; // Ambil NIP dari reviewer
        }


        // Tentukan role_name
        $role_name = $user->reviewer && $user->reviewer->role
            ? $user->reviewer->role->role_name
            : 'Mahasiswa'; // Default role jika tidak ada role reviewer

        // Ambil data beasiswa jika user adalah mahasiswa
        $beasiswa = $user->mahasiswa
            ? $user->mahasiswa->penerimaBeasiswa()->with('beasiswa')->get()
            : collect(); // Gunakan collection kosong jika bukan mahasiswa

            $mahasiswa = mahasiswa::where('user_id', $user_id)->first();
        // Kirim data ke view
        return view('pages.Pengaturan.index', compact(
            'user_img',
            'user_id',
            'email',
            'nama_depan',
            'nama_belakang',
            'phone',
            'user_img',
            'no_hp',
            'jk',
            'role_name',
            'nim', // Kirimkan NIM jika ada
            'nip', // Kirimkan NIP jika ada
            'beasiswa',// Kirimkan data beasiswa jika user adalah mahasiswa
            'mahasiswa',
            'user'
        ));
    }

    /**
     * Update the specified resource in storage.
     */


    public function updatefoto(Request $request, string $id)
    {
        if (! Auth::check()) {
            return redirect('login');
        }

        // 1) Cek error upload level PHP (file terlalu besar menurut php.ini)
        if (isset($_FILES['new_img']) && is_array($_FILES['new_img'])) {
            $err = $_FILES['new_img']['error'] ?? UPLOAD_ERR_OK;
            if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                $serverMax = ini_get('upload_max_filesize'); // mis. "2M"
                return redirect()->route('pengaturan.index')
                    ->with('error', "Ukuran file melebihi batas server ({$serverMax}). Silakan pilih file yang lebih kecil atau ubah konfigurasi server.");
            }
        }

        // 2) Validasi Laravel (max dalam KB)
        $maxKb = 2048;            // 2048 KB = 2 MB
        $maxMb = $maxKb / 1024;   // 2

        $rules = [
            'new_img' => "required|image|mimes:jpeg,png,jpg,gif,svg|max:{$maxKb}"
        ];

        $messages = [
            'new_img.required' => 'Silakan pilih gambar.',
            'new_img.image'    => 'File harus berupa gambar.',
            'new_img.mimes'    => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
            'new_img.max'      => "Ukuran file terlalu besar. Maksimum {$maxKb} KB (~{$maxMb} MB)."
        ];

        try {
            $validated = $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            // Kembalikan error validasi agar tampil di form
            return redirect()->route('pengaturan.index')
                ->with('error', $e->getMessage());
        }

        // 3) Upload & update (bungkus dengan try/catch untuk safety)
        try {
            if (! $request->hasFile('new_img')) {
                // kemungkinan file tidak ikut terupload (server limit) — tangani ulang
                return redirect()->route('pengaturan.index')
                    ->with('error', 'Tidak ada file terunggah. Pastikan ukuran file tidak melebihi batas.');
            }

            $newRequest = new Request();
            $newRequest->files->set('file', $request->file('new_img'));
            $newRequest->merge(['path' => 'foto']);

            $fileController = new FileController();
            $response = $fileController->uploadFileLocal($newRequest);

            $responseData = $response->getData(true);

            if (isset($responseData['url'])) {
                \App\Models\User::where('id', $id)->update(['foto' => $responseData['url']]);

                return redirect()->route('pengaturan.index')
                    ->with('success', 'Profile updated successfully');
            }

            return redirect()->route('pengaturan.index')
                ->with('error', 'Gagal mengunggah gambar. Silakan coba lagi.');
        } catch (\Exception $e) {
            Log::error("Error updating profile photo for user {$id}", ['exception' => $e->getMessage()]);
            return redirect()->route('pengaturan.index')
                ->with('error', $e->getMessage());
        }
    }


    public function updateprofil(Request $request, string $id)
    {
        // Pastikan user terautentikasi
        if (!Auth::check()) {
            return redirect('login');
        }

        $user_id = Auth::id();

        try {
            // Cari data mahasiswa
            $mahasiswa = Mahasiswa::where('user_id', $user_id)->first();
            // Cari data reviewer
            $reviewer = Reviewer::where('user_id', $user_id)->first();

            // Jika user adalah mahasiswa, perbolehkan update seluruh profil
            if ($mahasiswa) {
                // Validasi data mahasiswa
                $request->validate([
                    'nama_depan' => 'required|string|max:255',
                    'nama_belakang' => 'required|string|max:255',
                    'jk' => 'required|string|in:Pria,Wanita',
                    'nim' => 'nullable|string|max:20',
                    'no_hp' => 'nullable|string|max:15',
                ]);

                // Cari user berdasarkan ID
                $user = User::findOrFail($id);

                // Update data user
                $user->update([
                    'nama_depan' => $request->input('nama_depan', $user->nama_depan),
                    'nama_belakang' => $request->input('nama_belakang', $user->nama_belakang),
                    'jenis_kelamin' => $request->input('jk', $user->jenis_kelamin),
                ]);

                // Update data mahasiswa
                $mahasiswa->update([
                    'nim' => $request->input('nim', $mahasiswa->nim),
                    'no_hp' => $request->input('no_hp', $mahasiswa->no_hp),
                ]);

                return redirect()->route('pengaturan.index')->with('success', 'Profil mahasiswa berhasil diperbarui.');
            }

            // Jika user adalah reviewer, batasi update pada data yang diperbolehkan
            if ($reviewer) {
                // Validasi data reviewer
                $request->validate([
                    'nama_depan' => 'required|string|max:255',
                    'nama_belakang' => 'required|string|max:255',
                    'jk' => 'required|string|in:Pria,Wanita',
                    'email' => 'required|string|email|unique:users,email',
                ]);

                // Cari user berdasarkan ID
                $user = User::findOrFail($id);

                // Update data user reviewer
                $user->update([
                    'nama_depan' => $request->input('nama_depan', $user->nama_depan),
                    'nama_belakang' => $request->input('nama_belakang', $user->nama_belakang),
                    'jenis_kelamin' => $request->input('jk', $user->jenis_kelamin),
                    'email' => $request->input('email', $user->email)
                ]);

                return redirect()->route('pengaturan.index')->with('success', 'Profil reviewer berhasil diperbarui.');
            }

            return redirect()->route('pengaturan.index')->with('error', 'User tidak ditemukan.');

        } catch (\Exception $e) {
            return redirect()->route('pengaturan.index')->with('error', 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi. Peringatan! NIM tidak bisa diganti jika anda telah menerima beasiswa');
        }
    }
}
