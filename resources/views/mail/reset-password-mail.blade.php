<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* Gaya dasar email */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007BFF;
            color: #ffffff;
            text-align: center;
            padding: 20px 0;
        }
        h1 {
            margin: 0;
            padding: 0;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            background-color: #007BFF;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Hallo, {{ $data['full_name'] }}</p>
        <div class="content" style="text-align:center">
        
            <span style="text-align: center; font-weight:bold; margin-top:10px; font-size:32px;">{{ $data['new_password'] }}</span>
            <p>Anda telah meminta untuk mengatur ulang kata sandi Anda. Silakan klik tombol di bawah ini untuk mengatur ulang kata sandi Anda sebelum kedaluarsa dalam 60 menit kedepan.</p>
            <a class="button" href="http://localhost:5173/admin/confirm/{{ $data['email'] }}">Verifikasi</a>
            <p>Jika Anda tidak meminta pengaturan ulang kata sandi ini, harap abaikan email ini.</p>
            <p>Sandi anda tidak akan di reset ulang jika anda tidak melakukan verifikasi.</p>
        </div>
        <div class="footer">
            <p>Email ini dikirimkan kepada Anda sebagai tanggapan atas permintaan pengaturan ulang kata sandi.</p>
        </div>
    </div>
</body>
</html>
