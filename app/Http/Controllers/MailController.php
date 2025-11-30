<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\Controller;
use App\Mail\NotificationMail;
use App\Models\PengajuanBeasiswa;
use App\Models\Reviewer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function getNotificationData(bool $isReviewer)
    {
        if ($isReviewer) {
            $notification = DB::table('notifikasi')
                ->join('kode_status', 'kode_status.id', '=', 'notifikasi.status')
                ->select('user_id', 'isi_status')
                ->latest('created_at')
                ->skip(1)
                ->take(1)
                ->first();
        } else {
            $notification = DB::table('notifikasi')->join('kode_status', 'kode_status.id', '=', 'notifikasi.status')
                ->select('user_id', 'isi_status')
                ->latest('created_at')
                ->first();
        }


        if (!$notification) {
            return null;
        }

        $emailMahasiswa = User::where('id', '=', $notification->user_id)->first();

        if ($emailMahasiswa) {
            $notification->email = $emailMahasiswa->email;
        }


        return $notification;
    }


    public function mahasiswaPengajuanMessage(string $nim, string $id)
    {
        $bs = new BeasiswaController();
        $beasiswaData = $bs->getBeasiswaDataBaseOnBeasiswaId($id);

        return  [
            'nama' => "Pengajuan beasiswa pada program beasiswa " . $beasiswaData->nama_beasiswa .
                " oleh mahasiswa dengan NIM " . $nim,
            'message' => "Yth. Mahasiswa,\n\n" .
                "Kami ingin memberitahukan bahwa pengajuan beasiswa Anda pada program beasiswa " .
                $beasiswaData->nama_beasiswa . " telah diterima dan akan segera di proses. " .
                "Pengajuan ini diajukan oleh mahasiswa dengan NIM: " . $nim . "\n\n" .
                "Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi kami.\n\n" .
                "Hormat kami,\n" .
                "Tim Beasiswa"
        ];
    }

    public function reviewerPengajuanMessage(string $nim, string $id)
    {
        $bs = new BeasiswaController();
        $beasiswaData = $bs->getBeasiswaDataBaseOnBeasiswaId($id);

        return  [
            'nama' => "Pengajuan beasiswa pada program beasiswa " . $beasiswaData->nama_beasiswa .
                " oleh mahasiswa dengan NIM " . $nim,
            'message' => "Yth. Reviewer Staff Kemahasiswaan,\n\n" .
                "Kami ingin memberitahukan bahwa pengajuan beasiswa Anda pada program beasiswa " .
                $beasiswaData->nama_beasiswa  .
                " Pengajuan ini diajukan oleh mahasiswa dengan NIM: " . $nim . "\n\n" .
                "Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi kami.\n\n" .
                "Hormat kami,\n" .
                "Tim Beasiswa"
        ];
    }

    public function verifikasiEmailMessage($userId)
    {
        // Hash the user ID to create a verification token
        $hashedUserId = Hash::make($userId);

        // Save the hashed token to the database
        $user = User::find($userId);
        $user->email_verification_token = $hashedUserId;
        $user->save();

        // Generate the verification link with the token as a query parameter
        $verificationLink = route('verify-email') . '?token=' . urlencode($hashedUserId);

        // Construct the message content
        $messageContent = "Yth. Mahasiswa,\n\n" .
            "Terima kasih telah mendaftar di Sistem Informasi Kemahasiswaan. Mohon untuk memverifikasi email Anda dengan mengklik tautan berikut:\n\n" .
            $verificationLink . "\n\n" .
            "Jika Anda tidak merasa melakukan pendaftaran, harap abaikan email ini.\n\n" .
            "Hormat kami,\n" .
            "Tim Sistem Informasi Kemahasiswaan";

        // Return the email details including the subject, recipient email, and message
        return [
            'email' => $user->email,
            'nama' => "Verifikasi Email - Sistem Informasi Kemahasiswaan",
            'message' => $messageContent
        ];
    }

    public function sendMail(Request $request, bool $isReviewer, bool $isRegister)
    {

        $validated = $request->validate([
            'nama' => 'required|string',
            'message' => 'required|string',
        ]);

        if($isRegister){
            $userData = $request;
        } else{
            $userData = $this->getNotificationData($isReviewer);

            if (!$userData || empty($userData->email)) {
                return response()->json(['message' => 'No email found for the notification'], 404);
            }
        }

        $data = [
            'name' => $validated['nama'],
            'message' => $validated['message'],
        ];

        try {
            // Attempt to send the email
            Mail::to($userData->email)->send(new NotificationMail($data));

            // Return success response
            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            // Return an error response
            return response()->json([
                'message' => 'Failed to send email',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function notifyReviewer(Request $request)
    {
        $request->validate([
            'pengajuanId' => 'required|integer',
            'reviewerId' => 'required|integer',
        ]);

        // Retrieve Pengajuan and Reviewer details
        $pengajuan = PengajuanBeasiswa::find($request->pengajuanId);
        $reviewer = Reviewer::find($request->reviewerId);

        if (!$pengajuan || !$reviewer) {
            return response()->json(['message' => 'Pengajuan or Reviewer not found'], 404);
        }

        // Prepare email data
        $data = [
            'name' => 'Pemberitahuan Pengajuan Beasiswa',
            'message' => "Pengajuan beasiswa oleh mahasiswa dengan NIM: {$pengajuan->nim} telah selesai diproses.",
        ];

        // Fetch the reviewer's email
        $user = User::find($reviewer->user_id);
        if ($user && $user->email) {
            // Send email using NotificationMail
            Mail::to($user->email)->send(new NotificationMail($data));

            return response()->json(['message' => 'Notification email sent successfully']);
        }

        return response()->json(['message' => 'Reviewer email not found'], 404);
    }
}
