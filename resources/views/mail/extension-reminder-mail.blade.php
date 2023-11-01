<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            font-size: 16px;
            color: #666;
        }

        .bold {
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            background: #3498db;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rental Reminder</h1>
        <p>Hello <span class="bold">{{ $data['full_name'] }}</span>,</p>
        <p>Thank you for choosing <span class="bold">{{ env('MAIL_APP_NAME') }}</span> for your rental. Your rental period is about to expire, and we encourage you to extend your rental by visiting our store.</p>
        <p><span class="bold">Motor Type:</span> {{ $data['motor_name'] }} ({{ $data['total_unit'] }})</p>
        <p><span class="bold">Package:</span> {{ $data['package'] }}</p>
        <p>Your rental is set to expire on: {{ $data['return_date'] }}</p>
        <p>We appreciate your choice to rent from <span class="bold">{{ env('MAIL_APP_NAME') }}</span>!</p>
        <p>Warm regards,</p>
        <p>The <span class="bold">{{ env('MAIL_APP_NAME') }} Customer Service Team</span></p>
        <a class="btn" href="http://localhost:5173/">Extend Rental</a>
    </div>
</body>
</html>
