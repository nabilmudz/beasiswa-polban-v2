<!DOCTYPE html>
<html>
<head>
    <style>
        /* Tambahkan CSS Anda di sini */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e3e6f0;
            border-radius: 5px;
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header img {
            max-width: 150px;
        }
        .email-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('assets/img/logo-polban.png') }}" alt="Logo">
            <h2>Reset Password</h2>
        </div>
        <p>Halo,</p>
        <p>Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah ini untuk mereset password Anda:</p>
        <p class="flex justify-center items-center" style="text-align: center; font-weight:bold">
            <!-- Form with Button -->
            <form action="{{ $actionUrl }}" method="GET" style="text-align: center;">
                <button type="submit" style="background-color: #ff7300; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; display: inline-block;">
                    Reset Password
                </button>
            </form>
        </p>

        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
        <div class="email-footer">
            <p>&copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
