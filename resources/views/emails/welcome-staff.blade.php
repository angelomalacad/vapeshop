<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Vape Expo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #0d6efd;
        }
        .header img {
            max-height: 60px;
        }
        .header h1 {
            color: #0d6efd;
            margin: 10px 0 0;
            font-size: 24px;
        }
        .content {
            padding: 20px 0;
        }
        .credentials {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0d6efd;
        }
        .credentials p {
            margin: 5px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .button-verify {
            background-color: #28a745;
        }
        .button-verify:hover {
            background-color: #218838;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffecb5;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            font-size: 13px;
        }
        .note {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo">
            <h1>Welcome to Vape Expo!</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $user->name }}</strong>,</p>
            
            <p>Your account has been created for the Vape Expo management system.</p>
            
            <div class="note">
                <strong>📧 Email Verification Required:</strong> Please verify your email address to activate your account.
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button button-verify">
                    ✅ Verify My Email Address
                </a>
            </div>
            
            <div class="credentials">
                <p><strong>📧 Email:</strong> {{ $user->email }}</p>
                @if($password)
                    <p><strong>🔑 Temporary Password:</strong> {{ $password }}</p>
                    <div class="warning">
                        <strong>⚠️ Important:</strong> Please change your password after your first login for security reasons.
                    </div>
                @endif
                <p><strong>👤 Role:</strong> {{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
                <p><strong>🏢 Branch:</strong> {{ $user->branch->name ?? 'N/A' }}</p>
            </div>
            
            <p>After verifying your email, you can log in to the system using your email and the password provided above.</p>
            
            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="button">Login to Your Account →</a>
            </div>
            
            <p>Once logged in, you will be able to:</p>
            <ul>
                @if($user->role == 'branch_admin')
                    <li>Manage your branch inventory</li>
                    <li>Process sales through Point of Sale (POS)</li>
                    <li>Request stock transfers from other branches</li>
                    <li>Generate reports for your branch</li>
                @else
                    <li>Assist with daily operations</li>
                    <li>Process customer transactions</li>
                    <li>Update inventory levels</li>
                    <li>Handle customer inquiries</li>
                @endif
            </ul>
            
            <div class="note">
                <strong>⚠️ Note:</strong> The verification link will expire in 60 minutes. If you didn't receive the email or the link expired, please contact the system administrator.
            </div>
            
            <p>If you have any questions or need assistance, please contact the system administrator.</p>
            
            <p>Best regards,<br>
            <strong>Carlo Caranto</strong><br>
            Owner, Vape Expo<br>
            📞 0960 328 0432</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Vape Expo. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>