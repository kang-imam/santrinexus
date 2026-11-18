<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <meta name="supported-color-schemes" content="dark">
    <title>Atur Ulang Password | {{ config('company.name') }}</title>
    <style>
        :root {
            color-scheme: dark;
            supported-color-schemes: dark
        }

        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt
        }

        table {
            border-collapse: collapse !important
        }

        img {
            border: 0;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic
        }

        @media screen and (max-width:620px) {
            .email-wrapper {
                padding: 20px 10px !important
            }

            .email-container {
                width: 100% !important
            }

            .email-header {
                padding: 28px 20px !important
            }

            .email-content {
                padding: 30px 22px !important
            }

            .email-footer {
                padding: 24px 18px !important
            }

            .email-heading {
                font-size: 27px !important
            }

            .email-button {
                display: block !important;
                padding: 16px 18px !important
            }

            .email-logo {
                width: 165px !important
            }
        }

        @media screen and (max-width:360px) {
            .email-content {
                padding: 25px 16px !important
            }

            .email-heading {
                font-size: 24px !important
            }
        }
    </style>
</head>

<body
    style="margin:0;padding:0;width:100%;background-color:#030712;font-family:'Plus Jakarta Sans',Inter,Arial,sans-serif;color:#f8fafc;">
    <div
        style="display:none;font-size:1px;color:#030712;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
        Assalamu'alaikum. Kami menerima permintaan pengaturan ulang password akun {{ config('company.name') }} Anda.
    </div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="width:100%;background-color:#030712;">
        <tr>
            <td class="email-wrapper" align="center" style="padding:44px 18px;">
                <table class="email-container" role="presentation" width="600" cellpadding="0" cellspacing="0"
                    border="0"
                    style="width:100%;max-width:600px;background-color:#0b1220;border:1px solid #1e293b;border-radius:20px;overflow:hidden;">
                    <tr>
                        <td height="4" style="height:4px;background-color:#22d3ee;font-size:0;line-height:0;">&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td class="email-header" align="center"
                            style="padding:36px 32px 30px;border-bottom:1px solid #1e293b;">
                            <a href="{{ config('company.url') }}" target="_blank" rel="noopener noreferrer">
                                <img class="email-logo" src="{{ asset(config('company.brand.logo.primary')) }}"
                                    width="185" alt="{{ config('company.name') }}"
                                    style="display:block;width:185px;max-width:100%;height:auto;margin:0 auto;">
                            </a>
                            <p
                                style="margin:13px 0 0;font-size:10px;font-weight:700;line-height:1.8;letter-spacing:1.8px;text-transform:uppercase;color:#64748b;">
                                {{ config('company.brand.tagline') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-content" style="padding:42px 46px 38px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom:23px;">
                                        <p
                                            style="margin:0;font-family:Georgia,'Times New Roman',serif;font-size:21px;line-height:1.9;color:#67e8f9;direction:rtl;">
                                            السَّلَامُ عَلَيْكُمْ وَرَحْمَةُ اللَّهِ وَبَرَكَاتُهُ</p>
                                        <p style="margin:5px 0 0;font-size:11px;line-height:1.8;color:#64748b;">
                                            Assalamu'alaikum warahmatullahi wabarakatuh</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:24px;">
                                        <table role="presentation" width="66" height="66" cellpadding="0"
                                            cellspacing="0" border="0"
                                            style="width:66px;height:66px;background-color:#111c2e;border:1px solid #155e75;border-radius:19px;">
                                            <tr>
                                                <td align="center" valign="middle"
                                                    style="font-size:29px;line-height:66px;color:#67e8f9;">&#128273;
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <p
                                            style="margin:0 0 11px;font-size:10px;font-weight:800;line-height:1.7;letter-spacing:2px;color:#22d3ee;">
                                            ACCOUNT SECURITY</p>
                                        <h1 class="email-heading"
                                            style="margin:0 0 16px;font-family:Georgia,'Times New Roman',serif;font-size:30px;font-weight:700;line-height:1.4;color:#f8fafc;">
                                            Atur Ulang Password</h1>
                                        <p style="margin:0 0 30px;font-size:13px;line-height:1.9;color:#94a3b8;">Semoga
                                            Anda senantiasa dalam lindungan dan keberkahan Allah SWT. Kami menerima
                                            permintaan untuk mengatur ulang password akun {{ config('company.name') }}
                                            Anda.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:23px;">
                                        <p style="margin:0 0 5px;font-size:13px;line-height:1.8;color:#94a3b8;">Kepada
                                            Yth.</p>
                                        <p
                                            style="margin:0;font-size:18px;font-weight:800;line-height:1.7;color:#f8fafc;">
                                            {{ $user->name }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:27px;">
                                        <p style="margin:0;font-size:13px;line-height:2;color:#94a3b8;">Untuk menjaga
                                            keamanan akun Anda, silakan gunakan tombol berikut untuk membuat password
                                            baru. Pastikan Anda menyelesaikan proses ini sebelum tautan kedaluwarsa.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:30px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="center" bgcolor="#22d3ee"
                                                    style="background-color:#22d3ee;border-radius:10px;">
                                                    <a class="email-button" href="{{ $url }}" target="_blank"
                                                        rel="noopener noreferrer"
                                                        style="display:inline-block;padding:16px 38px;border:1px solid #22d3ee;border-radius:10px;color:#030712;font-size:14px;font-weight:800;line-height:1.5;text-decoration:none;">Atur
                                                        Ulang Password&nbsp; &#8594;</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:22px 23px;background-color:#111c2e;border:1px solid #1e293b;border-radius:13px;">
                                        <p
                                            style="margin:0 0 12px;font-size:11px;font-weight:800;letter-spacing:1.3px;color:#67e8f9;">
                                            &#128274; INFORMASI KEAMANAN</p>
                                        <p style="margin:0;font-size:12px;line-height:2;color:#94a3b8;">Tautan
                                            pengaturan ulang password berlaku selama <strong
                                                style="color:#f8fafc;">{{ $count }} menit</strong> sejak
                                            permintaan dibuat.</p>
                                        <p style="margin:10px 0 0;font-size:12px;line-height:2;color:#94a3b8;">Apabila
                                            Anda tidak merasa mengajukan permintaan ini, silakan abaikan email ini.
                                            Password akun Anda tidak akan berubah selama tautan tidak digunakan.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top:29px;">
                                        <p
                                            style="margin:0 0 10px;font-size:12px;font-weight:700;line-height:1.8;color:#94a3b8;">
                                            Jika tombol tidak dapat dibuka</p>
                                        <p style="margin:0 0 10px;font-size:11px;line-height:1.8;color:#64748b;">Salin
                                            tautan berikut dan buka melalui browser Anda:</p>
                                        <p
                                            style="margin:0;padding:13px 14px;background-color:#030712;border:1px solid #1e293b;border-radius:9px;font-size:11px;line-height:1.9;color:#22d3ee;word-break:break-all;overflow-wrap:anywhere;">
                                            {{ $url }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top:32px;">
                                        <p
                                            style="margin:0 0 7px;font-family:Georgia,'Times New Roman',serif;font-size:17px;line-height:1.9;color:#67e8f9;">
                                            وَالسَّلَامُ عَلَيْكُمْ وَرَحْمَةُ اللَّهِ وَبَرَكَاتُهُ</p>
                                        <p style="margin:0;font-size:11px;line-height:1.8;color:#64748b;">
                                            Wassalamu'alaikum warahmatullahi wabarakatuh</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-footer" align="center"
                            style="padding:28px 30px;background-color:#080e1a;border-top:1px solid #1e293b;">
                            <img src="{{ asset(config('company.brand.logo.icon')) }}" width="34" height="34"
                                alt="" style="display:block;width:34px;height:34px;margin:0 auto 13px;">
                            <p style="margin:0 0 7px;font-size:12px;font-weight:800;line-height:1.7;color:#f8fafc;">
                                {{ config('company.name') }}</p>
                            <p style="margin:0 0 15px;font-size:10px;line-height:1.8;color:#64748b;">
                                {{ config('company.brand.short_tagline') }}</p>
                            <p style="margin:0 0 7px;font-size:10px;line-height:1.8;color:#64748b;">Email otomatis dari
                                {{ config('company.name') }}. Mohon jangan membalas email ini.</p>
                            <p style="margin:0;font-size:10px;line-height:1.8;color:#475569;">&copy;
                                {{ date('Y') }} {{ config('company.name') }}. All rights reserved.</p>
                            <p style="margin:5px 0 0;font-size:10px;line-height:1.8;color:#475569;">
                                {{ config('company.legal_name') }}</p>
                        </td>
                    </tr>
                </table>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:600px;">
                    <tr>
                        <td align="center" style="padding:19px 15px 0;">
                            <p style="margin:0;font-size:10px;line-height:1.8;color:#475569;">Pesan ini dikirim untuk
                                membantu menjaga keamanan akun Anda.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
