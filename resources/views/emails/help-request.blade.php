<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Request from {{ $user->fullname }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }
        .title {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 10px 0;
        }
        .user-info {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            width: 120px;
            flex-shrink: 0;
        }
        .info-value {
            color: #1f2937;
        }
        .message-section {
            margin: 20px 0;
        }
        .message-content {
            background-color: #f3f4f6;
            border-left: 4px solid #4f46e5;
            padding: 20px;
            border-radius: 4px;
            white-space: pre-wrap;
            font-style: italic;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-admin {
            background-color: #dc2626;
            color: white;
        }
        .badge-facilitator {
            background-color: #059669;
            color: white;
        }
        .badge-personnel {
            background-color: #2563eb;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🎓 Atommart LMS</div>
            <h1 class="title">Help Request Received</h1>
            <p style="color: #6b7280; margin: 0;">A user has submitted a help request through the platform</p>
        </div>

        <div class="user-info">
            <h3 style="margin: 0 0 15px 0; color: #1f2937;">User Information</h3>
            
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value">{{ $user->fullname }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Role:</span>
                <span class="info-value">
                    <span class="badge badge-{{ $user->role }}">{{ $user->role_display }}</span>
                </span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Department:</span>
                <span class="info-value">{{ $user->department->name }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Position:</span>
                <span class="info-value">{{ $user->position ?? 'Not specified' }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Points:</span>
                <span class="info-value">{{ $user->points ?? 0 }} points</span>
            </div>
        </div>

        <div class="message-section">
            <h3 style="margin: 0 0 15px 0; color: #1f2937;">User Message</h3>
            <div class="message-content">{{ $message }}</div>
        </div>

        <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; color: #92400e; font-size: 14px;">
                <strong>Action Required:</strong> Please review this help request and respond to the user at their earliest convenience.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">
                This message was sent from the Atommart LMS platform on {{ now()->format('F j, Y \a\t g:i A') }}.
            </p>
            <p style="margin: 10px 0 0 0;">
                If you believe this is an error or spam, please contact your system administrator.
            </p>
        </div>
    </div>
</body>
</html>
