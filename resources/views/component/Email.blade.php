<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Pemberitahuan Pengajuan Beasiswa' }}</title>
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
        <!-- Header Section -->
        <div class="email-header">
            <img src="{{ asset('assets/img/logo-polban.png') }}" alt="Logo">
        </div>

        <!-- Content Section -->
        <div class="content">
            <p>{{ $data['message'] }}</p>

            <p>Terima kasih atas perhatian Anda.</p>
            <p>Salam hangat,</p>
            <p>Tim Beasiswa Politeknik Negeri Bandung</p>

        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Politeknik Negeri Bandung, Jl. Gegerkalong Hilir, Kota Bandung, Indonesia</p>
        </div>
    </div>
</body>
</html>
