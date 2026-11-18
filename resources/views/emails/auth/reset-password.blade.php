<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <meta name="supported-color-schemes" content="dark">
    <title>Atur Ulang Password {{ config('company.name') }}</title>
    <style>
        :root {
            color-scheme: dark;
            supported-color-schemes: dark
        }

        @media only screen and (max-width:600px) {
            .email-container {
                width: 100% !important
            }

            .email-content {
                padding: 28px 22px !important
            }

            .email-header {
                padding: 28px 20px 22px !important
            }

            .email-footer {
                padding: 22px 20px !important
            }

            .email-title {
                font-size: 23px !important
            }

            .email-button {
                display: block !important;
                text-align: center !important
            }
        }
    </style>
</head>

<body
    style="margin:0;padding:0;background-color:#030712;font-family:'Plus Jakarta Sans',Inter,Arial,Helvetica,sans-serif;color:#f8fafc;-webkit-font-smoothing:antialiased;">
    <div
        style="display:none;font-size:1px;color:#030712;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
        Permintaan pengaturan ulang password akun {{ config('company.name') }}. Gunakan tautan aman untuk membuat
        password baru.</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="width:100%;background-color:#030712;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table class="email-container" role="presentation" width="560" cellpadding="0" cellspacing="0"
                    border="0"
                    style="width:100%;max-width:560px;background-color:#0b1220;border:1px solid #1e293b;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td class="email-header" align="center"
                            style="padding:32px 32px 26px;border-bottom:1px solid #1e293b;">
                            <div
                                style="font-size:26px;font-weight:800;letter-spacing:-1.2px;line-height:1.3;color:#f8fafc;">
                                {{ config('company.brand.first_name') }}<span
                                    style="color:#22d3ee;">{{ config('company.brand.second_name') }}</span></div>
                            <div
                                style="margin-top:9px;font-size:10px;font-weight:600;letter-spacing:1.8px;line-height:1.6;text-transform:uppercase;color:#64748b;">
                                {{ config('company.brand.tagline') }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-content" style="padding:38px 36px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom:24px;">
                                        <table role="presentation" width="60" height="60" cellpadding="0"
                                            cellspacing="0" border="0"
                                            style="width:60px;height:60px;background-color:#164e63;border:1px solid #155e75;border-radius:16px;">
                                            <tr>
                                                <td align="center" valign="middle"
                                                    style="font-size:27px;line-height:60px;color:#67e8f9;">&#128273;
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <h1 class="email-title"
                                            style="margin:0 0 14px;font-size:26px;font-weight:800;line-height:1.4;letter-spacing:-.5px;color:#f8fafc;">
                                            Atur Ulang Password</h1>
                                        <p style="margin:0 0 28px;font-size:14px;line-height:1.8;color:#94a3b8;">Kami
                                            menerima permintaan untuk mengatur ulang password akun
                                            {{ config('company.name') }} Anda.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="margin:0 0 12px;font-size:14px;line-height:1.7;color:#f8fafc;">Halo,
                                            <strong style="font-weight:700;color:#ffffff;">{{ $user->name }}</strong>.
                                        </p>
                                        <p style="margin:0 0 28px;font-size:14px;line-height:1.9;color:#94a3b8;">Untuk
                                            membuat password baru, klik tombol di bawah ini. Tautan hanya dapat
                                            digunakan selama masa berlaku yang ditentukan.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding:0 0 30px;">
                                        <a class="email-button" href="{{ $url }}" target="_blank"
                                            rel="noopener noreferrer"
                                            style="display:inline-block;padding:15px 32px;background-color:#22d3ee;border:1px solid #22d3ee;border-radius:10px;color:#030712;font-size:14px;font-weight:800;line-height:1.5;text-align:center;text-decoration:none;">Atur
                                            Ulang Password</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:20px 22px;background-color:#111c2e;border:1px solid #1e293b;border-radius:12px;">
                                        <p
                                            style="margin:0 0 10px;font-size:11px;font-weight:800;letter-spacing:1.1px;color:#67e8f9;">
                                            INFORMASI KEAMANAN</p>
                                        <p style="margin:0;font-size:12px;line-height:1.9;color:#94a3b8;">Tautan ini
                                            berlaku selama <strong
                                                style="font-weight:700;color:#f8fafc;">{{ $count }}
                                                menit</strong> sejak email dikirim.</p>
                                        <p style="margin:9px 0 0;font-size:12px;line-height:1.9;color:#94a3b8;">Jika
                                            Anda tidak meminta pengaturan ulang password, abaikan email ini. Password
                                            akun Anda tidak akan berubah.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top:28px;">
                                        <p style="margin:0 0 10px;font-size:12px;line-height:1.8;color:#64748b;">Jika
                                            tombol tidak berfungsi, salin dan buka tautan berikut melalui browser:</p>
                                        <p
                                            style="margin:0;overflow-wrap:anywhere;word-break:break-all;font-size:11px;line-height:1.9;color:#22d3ee;">
                                            {{ $url }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-footer" align="center" style="padding:25px 32px;border-top:1px solid #1e293b;">
                            <p style="margin:0 0 9px;font-size:11px;line-height:1.8;color:#64748b;">Email otomatis dari
                                {{ config('company.name') }}. Mohon jangan membalas email ini.</p>
                            <p style="margin:0;font-size:10px;line-height:1.8;color:#475569;">&copy;
                                {{ date('Y') }} {{ config('company.name') }}. All rights reserved.</p>
                            <p style="margin:7px 0 0;font-size:10px;line-height:1.8;color:#475569;">
                                {{ config('company.legal_name') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
