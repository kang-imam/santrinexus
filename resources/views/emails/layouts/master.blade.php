@php
    $company = config('company');
    $brand = $company['brand'] ?? [];
    $identity = $brand['identity'] ?? [];
    $logo = $brand['logo'] ?? [];
    $companyName = $company['name'] ?? config('app.name');
    $brandName = $brand['name'] ?? $companyName;
    $legalName = $company['legal_name'] ?? $companyName;
    $tagline = $brand['short_tagline'] ?? ($brand['tagline'] ?? '');
    $logoUrl = asset($logo['primary'] ?? 'logo_santrinexus.svg');
    $logoAlt = $logo['alt'] ?? $brandName;
    $primaryColor = $identity['primary_color'] ?? '#22D3EE';
    $secondaryColor = $identity['secondary_color'] ?? '#38BDF8';
    $backgroundColor = '#030712';
    $surfaceColor = '#0B1220';
    $textColor = $identity['text_color'] ?? '#F8FAFC';
    $mutedColor = $identity['muted_color'] ?? '#94A3B8';
    $supportEmail = $company['contact']['emails']['support'] ?? ($company['contact']['primary_email'] ?? null);
    $websiteUrl = $company['url'] ?? config('app.url');
    $websiteHost = parse_url($websiteUrl, PHP_URL_HOST);
    $copyright = '© ' . date('Y') . ' ' . $legalName . '. All rights reserved.';
    $emailTitle = $title ?? $brandName;
    $emailPreheader = $preheader ?? '';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $company['locale'] ?? app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <meta name="supported-color-schemes" content="dark">
    <title>{{ $emailTitle }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            background-color: {{ $backgroundColor }};
            font-family: Arial, Helvetica, sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%
        }

        table {
            border-collapse: collapse;
            border-spacing: 0
        }

        img {
            border: 0;
            outline: none;
            text-decoration: none
        }

        a {
            color: {{ $primaryColor }};
            text-decoration: none
        }

        .email-wrapper {
            width: 100%;
            background-color: {{ $backgroundColor }}
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto
        }

        .email-content {
            padding: 36px 40px;
            background-color: {{ $surfaceColor }}
        }

        .email-footer {
            padding: 28px 24px;
            text-align: center
        }

        @media only screen and (max-width:600px) {
            .email-content {
                padding: 28px 22px !important
            }

            .email-container {
                width: 100% !important
            }
        }
    </style>
</head>

<body
    style="margin:0;padding:0;background-color:{{ $backgroundColor }};color:{{ $textColor }};font-family:Arial,Helvetica,sans-serif">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent">{{ $emailPreheader }}</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="email-wrapper"
        bgcolor="{{ $backgroundColor }}">
        <tr>
            <td align="center" style="padding:36px 16px">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"
                    class="email-container" style="width:100%;max-width:600px">
                    <tr>
                        <td align="center" style="padding:0 20px 28px">
                            <a href="{{ $websiteUrl }}" target="_blank" style="display:inline-block">
                                <img src="{{ $logoUrl }}" width="120" alt="{{ $logoAlt }}"
                                    style="display:block;width:120px;max-width:100%;height:auto;margin:0 auto">
                            </a>
                            <p
                                style="margin:10px 0 0;color:{{ $textColor }};font-size:14px;font-weight:700;line-height:20px;letter-spacing:0.3px">
                                {{ $brandName }}
                            </p>
                            @if ($tagline)
                                <p
                                    style="margin:4px 0 0;color:{{ $mutedColor }};font-size:10px;line-height:16px;letter-spacing:1.2px;text-transform:uppercase">
                                    {{ $tagline }}
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="{{ $surfaceColor }}"
                            style="background-color:{{ $surfaceColor }};border:1px solid #1E293B;border-radius:16px;overflow:hidden">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td height="4" bgcolor="{{ $primaryColor }}"
                                        style="height:4px;background-color:{{ $primaryColor }};font-size:0;line-height:0">
                                        &nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="email-content"
                                        style="padding:36px 40px;background-color:{{ $surfaceColor }};color:{{ $textColor }}">
                                        @yield('content')
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-footer" style="padding:28px 24px;text-align:center">
                            <p style="margin:0 0 8px;color:{{ $mutedColor }};font-size:12px;line-height:20px">
                                {{ $copyright }}
                            </p>
                            @if ($supportEmail)
                                <p style="margin:0 0 8px;color:{{ $mutedColor }};font-size:12px;line-height:20px">
                                    Butuh bantuan? <a href="mailto:{{ $supportEmail }}"
                                        style="color:{{ $primaryColor }}">{{ $supportEmail }}</a>
                                </p>
                            @endif
                            <p style="margin:0;color:{{ $mutedColor }};font-size:11px;line-height:18px">
                                <a href="{{ $websiteUrl }}" target="_blank"
                                    style="color:{{ $secondaryColor }}">{{ $websiteHost }}</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
