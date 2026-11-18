{{ strtoupper(config('company.name')) }}
{{ config('company.brand.short_tagline') }}
ATUR ULANG PASSWORD
Halo, {{ $user->name }}.
Kami menerima permintaan untuk mengatur ulang password akun {{ config('company.name') }} Anda.
Untuk membuat password baru, buka tautan berikut melalui browser:
{{ $url }}
INFORMASI KEAMANAN
Tautan ini berlaku selama {{ $count }} menit sejak email dikirim.
Jika Anda tidak meminta pengaturan ulang password, abaikan email ini. Password akun Anda tidak akan berubah.
Email otomatis dari {{ config('company.name') }}. Mohon jangan membalas email ini.
© {{ date('Y') }} {{ config('company.name') }}. All rights reserved.
{{ config('company.brand.tagline') }}