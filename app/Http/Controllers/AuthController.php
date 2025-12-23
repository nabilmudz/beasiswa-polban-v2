<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Reviewer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\MailController;
use Exception;

class AuthController extends Controller
{
    protected $firebaseAuth;
    protected $mailController;

    public function __construct(MailController $mailController)
    {
        $this->mailController = $mailController;
    }
    /**
     * Show the login form.
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('beasiswa.index');
        }
        return view('pages.Auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@polban\.ac\.id$/',
            ],
            'password' => 'required|min:6',
        ], [
            'email.regex' => 'Gunakan email polban!',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        try {
            // Cari pengguna berdasarkan email
            $user = User::where('email', $email)->firstOrFail();

            if (!$user->isActive) {
                throw new \Exception('Akun belum teraktivasi, silakan selesaikan proses registrasi.');
            }

            // Verifikasi password
            if (!Hash::check($password, $user->password)) {
                throw new \Exception('Password salah.');
            }

            // Verifikasi email
            if (!$user->emailVerif) {
                throw new \Exception('Silahkan verifikasi email Anda.');
            }

            // Cek peran pengguna
            $mhs = Mahasiswa::where('user_id', $user->id)->first();
            if ($mhs) {
                session(['auth' => ['user' => $user, 'role' => 'mahasiswa', 'mhs' => $mhs]]);
                Auth::login($user);
            } else {
                $reviewer = Reviewer::where('user_id', $user->id)->first();
                if ($reviewer) {
                    session(['auth' => ['user' => $user, 'role' => 'reviewer', 'reviewer' => $reviewer]]);
                    Auth::login($user);
                } else {
                    throw new \Exception('User tidak ditemukan atau peran tidak valid.');
                }
            }

            // Regenerate session ID untuk mencegah serangan session fixation
            $request->session()->regenerate();

            // Redirect berdasarkan peran
            return $mhs ? redirect()->intended('/madding') : redirect()->intended('/dashboard');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/login')->with('error', 'Email tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', $e->getMessage());
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@polban\.ac\.id$/',
            ],
            'password' => 'required|min:6|confirmed',
        ], [
            'email.regex' => 'Gunakan email polban!',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        try {
            $user = User::where('email', $email)->first();

            if ($user) {
                // Jika user sudah ada
                if (! $user->isActive) {
                    // Update password user yang belum aktif
                    $user->update([
                        'password' => bcrypt($password),
                    ]);
                } else {
                    return redirect('/register')->with('error', 'Email telah terdaftar pada sistem.');
                }
            } else {
                // Jika user belum ada → buat baru
                $user = User::create([
                    'id' => User::max('id') + 1, // hati-hati kalau id auto increment
                    'email' => $email,
                    'password' => bcrypt($password),
                    'emailVerif' => false,
                ]);
            }

            // Kirim email verifikasi
            $emailController = new MailController();
            $emailController->sendMail(
                new Request($this->mailController->verifikasiEmailMessage($user->id)),
                false,
                true
            );

            return redirect()->route('auth.register-information', ['id' => $user->id]);

        } catch (\Exception $e) {
            return redirect('/register')->with('error', $e->getMessage());
        }
    }



    public function insertMahasiswaData(Request $request, string $id)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'nama_depan' => 'required|string',
                'nama_belakang' => 'required|string',
                'jenis_kelamin' => 'required|string',
                'nim' => 'required|string|size:9|unique:mahasiswa,nim',
                'semester' => 'required|integer|min:1|max:8',
                'tgl_lahir' => 'required|date',
                'prodi_id' => 'required|exists:prodi,id',
                'no_hp' => 'required|string|unique:mahasiswa,no_hp',
                'angkatan' => 'required|integer|digits:4',
                'ipk_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'ukt_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'status_beasiswa' => 'required|boolean',
                'nama_beasiswa_saat_ini' => 'required_if:status_beasiswa,1|nullable|string|max:255',
            ],[
                'nim.size' => 'NIM harus berjumlah 9 karakter.',
                'nim.required' => 'NIM wajib diisi.',
                'nim.unique' => 'NIM sudah terdaftar.',
                'semester.min' => 'Semester minimal 1.',
                'semester.max' => 'Semester maksimal 8.',
                'no_hp.unique' => 'Nomor HP sudah terdaftar.',
                'angkatan.digits' => 'Angkatan harus terdiri dari 4 digit.',
                'ipk_file.required' => 'File IPK wajib diupload.',
                'ipk_file.mimes' => 'File IPK harus berformat PDF, JPG, JPEG, atau PNG.',
                'ipk_file.max' => 'Ukuran file IPK maksimal 2MB.',
                'ukt_file.required' => 'File UKT wajib diupload.',
                'ukt_file.mimes' => 'File UKT harus berformat PDF, JPG, JPEG, atau PNG.',
                'ukt_file.max' => 'Ukuran file UKT maksimal 2MB.',
                'nama_beasiswa_saat_ini.required_if' => 'Nama beasiswa wajib diisi jika sedang menjalani beasiswa.',
            ]);

            // Handle file uploads
            $ipkPath = null;
            $uktPath = null;

            if ($request->hasFile('ipk_file')) {
                $ipkFile = $request->file('ipk_file');
                $ipkFileName = 'ipk_' . $request->nim . '_' . time() . '.' . $ipkFile->getClientOriginalExtension();
                $ipkPath = $ipkFile->storeAs('mahasiswa/ipk', $ipkFileName, 'public');
            }

            if ($request->hasFile('ukt_file')) {
                $uktFile = $request->file('ukt_file');
                $uktFileName = 'ukt_' . $request->nim . '_' . time() . '.' . $uktFile->getClientOriginalExtension();
                $uktPath = $uktFile->storeAs('mahasiswa/ukt', $uktFileName, 'public');
            }

            // Menambahkan data mahasiswa
            Mahasiswa::create([
                'user_id' => $id,
                'nim' => $request->nim,
                'semester' => $request->semester,
                'tgl_lahir' => $request->tgl_lahir,
                'prodi_id' => $request->prodi_id,
                'no_hp' => $request->no_hp,
                'angkatan' => $request->angkatan,
                'ipk_file' => $ipkPath,
                'ukt_file' => $uktPath,
                'status_beasiswa' => $request->status_beasiswa,
                'nama_beasiswa_saat_ini' => $request->status_beasiswa == 1 ? $request->nama_beasiswa_saat_ini : null,
            ]);

            // Memperbarui data pengguna
            User::where('id', '=', $id)->update([
                'nama_depan' => $request->nama_depan,
                'nama_belakang' => $request->nama_belakang,
                'jenis_kelamin' => $request->jenis_kelamin,
                'isActive' => true
            ]);

            // Mengarahkan ke halaman login setelah sukses
            return redirect('/login')->with('success','Akun berhasil terdaftar, silahkan cek email anda untuk verifikasi email');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Mendapatkan pesan kesalahan validasi
            $errors = implode(' ', $e->errors()['nim'] ?? []); // You can target a specific field like 'nim' or just display all errors

            // Jika terjadi kesalahan validasi, kembalikan ke halaman dengan error message
            return redirect('/register-information/' . $id)
                ->with('error', 'Ada kesalahan dalam validasi input. Silakan periksa kembali data Anda. ' . $errors)
                ->withInput(); // Menyertakan input yang telah dimasukkan agar tidak hilang
        } catch (\Exception $e) {
            // Handle any other general exceptions (database errors, etc.)
            return redirect('/register-information/' . $id)->with('error', $e->getMessage());
        }
    }

    /**
     * Handle reset password submission.
     */

     public function resetPassword(Request $request)
     {
         $request->validate([
             'email' => 'required|email|exists:users,email', // Ensure email exists in the database
         ]);

         $email = $request->email;

         try {
             // Send password reset email using Laravel's Password facade
             $status = Password::sendResetLink(['email' => $email]);

             if ($status === Password::RESET_LINK_SENT) {
                 return redirect('/reset-password')->with('success', 'Link Reset Password telah dikirimkan ke Email');
             }

             return redirect('/reset-password')->with('error', 'Gagal mengirimkan link reset password. Silakan coba lagi.');
         } catch (\Exception $e) {
             return response()->json(['message' => 'Failed to send password reset link. Please try again later.' . $e], 500);
         }
     }

     public function showResetForm(Request $request, $token = null)
    {
        return view('pages.Auth.change-password', ['token' => $token, 'email' => $request->email]);
    }



    public function changePassword(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
                'token' => 'required'
            ]);

            // Cek apakah token valid
            $response = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                    ])->save();
                }
            );

            if ($response == Password::PASSWORD_RESET) {
                return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login.');
            }

            // Jika gagal karena token atau email invalid
            return back()->withErrors(['email' => trans($response)]);

        } catch (Exception $e) {
            // Tangkap error tak terduga (misalnya DB connection, dll.)
            return back()->with('error', $e->getMessage());
        }
    }



    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function getRegisterInformation()
    {
        $prodi = Prodi::all();
        return view('pages.Auth.register-information', compact('prodi'));
    }

    public function showRegistrationForm()
    {
        return view('pages.Auth.register'); // Path to your registration view file
    }

    public function showResetPasswordForm()
    {
        return view('pages.Auth.reset-password'); // Path to your registration view file
    }

    public function verifyEmail(Request $request)
    {
        // Find the user who has this token (you would store this token in the database)
        $hashedToken = $request->query('token');
        $user = User::where('email_verification_token',  $hashedToken)->first();

        if (!$user) {
            return redirect('/login')->with('error', 'Invalid verification link.');
        }

        // Verify the token
        if (Hash::check($user->id, $hashedToken )) {
            // Set email verification status to true
            $user->update(['emailVerif' => true]);

            return redirect('/login')->with('success', 'Email Anda berhasil diverifikasi. Silakan login.');
        } else {
            return redirect('/login')->with('error', 'Invalid verification link.');
        }
    }
}
