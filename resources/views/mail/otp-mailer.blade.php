<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .message {
            padding: 20px;
        }

        .signature {
            text-align: center;
            margin-top: 20px;
        }

        .message h1 {
            font-size: 24px;
            color: #007BFF;
        }

        .message p {
            font-size: 16px;
            line-height: 1.6;
        }

        .otp-code {
            font-size: 28px !important;
            font-weight: bold;
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message">
            <h1>Kode OTP Anda</h1>
            <span>Halo {{$data['full_name']}},</span>
            <p>Kode OTP untuk registrasi anda sebagai admin:</p>
            <p class="otp-code">{{$data['otpCode']}}</p>
            <p>Harap gunakan kode ini untuk melanjutkan proses Anda.</p>
            <p>Jangan berikan kode ini kepada siapa pun untuk alasan keamanan.</p>
        </div>
        <div class="signature">
            <p>Terima kasih<br></p>
        </div>
    </div>
</body>
</html>
