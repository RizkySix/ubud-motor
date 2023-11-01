<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .email-container {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #f5f5f5;
    }

    .booking-details {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      margin-top: 20px;
    }

    .booking-details h2 {
      font-size: 20px;
    }

    .booking-list {
      list-style-type: none;
      padding: 0;
    }

    .booking-list-item {
      margin: 10px 0;
    }

    .booking-list-item strong {
      font-weight: bold;
    }

    .footer {
      margin-top: 40px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="booking-details">
      <h2>Thank You for Your Payment</h2>
      <p>We appreciate your payment and look forward to serving you. Below are the details of your booking:</p>
      <ul class="booking-list">
        <li class="booking-list-item">
          <strong>Your Name:</strong> {{ $data['full_name'] }}
        </li>
        <li class="booking-list-item">
          <strong>Motor:</strong> {{ $data['motor_name'] }}
        </li>
        <li class="booking-list-item">
          <strong>Package:</strong> {{ $data['package'] }}
        </li>
        <li class="booking-list-item">
          <strong>WhatsApp:</strong> {{ $data['whatsapp_number'] }}
        </li>
        <li class="booking-list-item">
          <strong>Amount:</strong> Rp. {{ number_format($data['amount'] , 0 , '.' , '.') }}
        </li>
        <li class="booking-list-item">
            <strong>Payment Status:</strong> <strong>Paid</strong>
        </li>
      </ul>

      <p>For further order details, please check by visiting our website and logging in to your account, <a href="http://localhost:5173/">http://localhost:5173/</a></p>
    </div>

    <div class="footer">
      &copy; 2023 {{ env('MAIL_APP_NAME') }}
    </div>
  </div>
</body>
</html>
