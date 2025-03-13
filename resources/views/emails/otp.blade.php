<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .otp {
            text-align: center;
            font-size: 32px;
            letter-spacing: 5px;
            font-weight: bold;
            margin: 30px 0;
            color: #4f46e5;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Email Verification</h1>
        </div>
        
        <p>Hello,</p>
        
        <p>Thank you for registering with our service. To complete your registration, please use the verification code below:</p>
        
        <div class="otp">{{ $otp }}</div>
        
        <p>This code will expire in 5 minutes. If you didn't request this verification, please ignore this email.</p>
        
        <div class="footer">
            <p>&copy; 2025 Auth System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>