<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        body { background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 0; width: 100% !important; }
        .wrapper { background-color: #f3f4f6; padding: 40px 20px; }
        .content { background-color: #ffffff; border-radius: 24px; margin: 0 auto; max-width: 600px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #4f46e5; padding: 40px; text-align: center; }
        .header img { height: 60px; margin-bottom: 16px; }
        .header h1 { color: #ffffff; font-size: 24px; font-weight: 800; margin: 0; letter-spacing: -0.025em; }
        .body { padding: 40px; }
        .body h2 { color: #1f2937; font-size: 20px; font-weight: 700; margin-top: 0; margin-bottom: 24px; }
        .body p { color: #4b5563; font-size: 16px; line-height: 1.6; margin-bottom: 24px; }
        .button-container { text-align: center; margin-bottom: 32px; }
        .button { background-color: #4f46e5; border-radius: 12px; color: #ffffff !important; display: inline-block; font-size: 16px; font-weight: 600; padding: 14px 32px; text-decoration: none; transition: background-color 0.2s; }
        .footer { padding: 40px; text-align: center; }
        .footer p { color: #9ca3af; font-size: 14px; margin: 0; }
        .divider { border-top: 1px solid #e5e7eb; margin: 32px 0; }
        @media only screen and (max-width: 600px) {
            .content { border-radius: 0; }
            .header, .body, .footer { padding: 32px 24px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content">
            <div class="header">
                <h1>{{ config('app.name') }}</h1>
            </div>

            <div class="body">
                @yield('content')
                
                <div class="divider"></div>
                <p style="font-size: 14px; color: #6b7280;">Jika Anda tidak merasa melakukan tindakan ini, mohon segera amankan akun Anda atau hubungi admin kami.</p>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta dilindungi.</p>
                <p style="margin-top: 8px;">Kecamatan Kembaran, Kabupaten Banyumas</p>
            </div>
        </div>
    </div>
</body>
</html>
