<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            font-size: 20px;
            color: #007BFF;
        }

        .message p {
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message">
            <h1>Payment Reminder</h1>
            <p>Hello {{$data['full_name']}},</p>
            <p>Thank you for choosing {{ env('MAIL_APP_NAME') }}. To complete your booking, please visit our store to make the payment , pick up your bike and <strong>start your advanture with us</strong> ❤️.</p>
            <p><strong>Motor Type:</strong> {{$data['motor_name']}} ({{$data['total_unit']}})</p>
            <p><strong>Package:</strong> {{$data['package']}}</p>
            <p><strong>Amount:</strong> Rp. {{$data['amount']}}</p>
            <p><strong>Payment due by:</strong> {{$data['expired_payment']}}</p>
            <p>We appreciate your business and look forward to serving you!</p>
        </div>
        <div class="signature">
            <p>Best regards,<br>{{ env('MAIL_APP_NAME') }} Customer Service Team</p>
        </div>
    </div>
</body>
</html>
