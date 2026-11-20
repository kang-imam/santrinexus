@extends('emails.layouts.master')
@section('title', 'Tautan Login SantriNexus')
@section('preheader', 'Gunakan tautan aman untuk masuk ke akun SantriNexus Anda.')
@section('content')
    @php
        $name = $user->name ?? 'Pengguna';
        $primaryColor = config('company.brand.identity.primary_color', '#22D3EE');
    @endphp
    <p style="margin:0 0 12px;color:#94A3B8;font-size:13px;line-height:20px">AKSES AKUN</p>
    <h1 style="margin:0 0 18px;color:#F8FAFC;font-size:25px;line-height:34px;font-weight:700">Halo, {{ $name }}!</h1>
    <p style="margin:0 0 18px;color:#CBD5E1;font-size:14px;line-height:24px">Kami menerima permintaan untuk masuk ke akun
        SantriNexus Anda. Gunakan tombol berikut untuk melanjutkan proses login.</p>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding:12px 0 24px">
                <a href="{{ $url }}" target="_blank"
                    style="display:inline-block;padding:14px 30px;border-radius:8px;background-color:{{ $primaryColor }};color:#030712;font-size:14px;line-height:20px;font-weight:700;text-align:center">Login
                    Sekarang</a>
            </td>
        </tr>
    </table>
    <p style="margin:0 0 12px;color:#CBD5E1;font-size:14px;line-height:24px">Tautan login ini hanya dapat digunakan satu
        kali dan berlaku selama <strong style="color:#F8FAFC">{{ $expirationMinutes }} menit</strong>.</p>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="padding:16px;border:1px solid #334155;border-radius:8px;background-color:#111C2E">
                <p style="margin:0 0 6px;color:#FBBF24;font-size:13px;line-height:20px;font-weight:700">Pemberitahuan
                    Keamanan</p>
                <p style="margin:0;color:#94A3B8;font-size:12px;line-height:21px">Jangan bagikan tautan ini kepada siapa
                    pun. Jika Anda tidak meminta tautan login, abaikan email ini.</p>
            </td>
        </tr>
    </table>
    <p style="margin:22px 0 8px;color:#94A3B8;font-size:12px;line-height:20px">Jika tombol di atas tidak berfungsi, salin
        dan buka tautan berikut melalui browser Anda:</p>
    <p style="margin:0;word-break:break-all;color:{{ $primaryColor }};font-size:12px;line-height:21px"><a
            href="{{ $url }}" target="_blank" style="color:{{ $primaryColor }}">{{ $url }}</a></p>
@endsection
