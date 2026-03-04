<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Verification Code</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .code-container {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #fff;
            border: 2px dashed #007bff;
            border-radius: 8px;
        }

        .code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #007bff;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>UMAEP</h1>
    </div>
    <div class="content">
        <p>Hello {{ $userName }},</p>
        <p>Thank you for registering with UMAEP. To complete your registration, please use the 6-digit verification code
            below:</p>

        <div class="code-container">
            <span class="code">{{ $code }}</span>
        </div>

        <p>This code will expire in 15 minutes. If you did not request this code, please ignore this email.</p>

        <p>Best regards,<br>The UMAEP Team</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} UMAEP. All rights reserved.
    </div>
</body>

</html>