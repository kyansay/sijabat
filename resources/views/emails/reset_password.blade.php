<x-mail::message>
# Permintaan Reset Password

Kami menerima permintaan untuk mereset password akun Anda di SI-JABAT.

Silakan klik tombol di bawah ini untuk membuat password baru. Link ini hanya berlaku selama 60 menit.

@php
    // Ganti URL ini dengan URL halaman frontend form reset password Anda nantinya
    $urlReset = url('/reset-password?token=' . $token . '&email=' . $email);
@endphp

<x-mail::button :url="$urlReset" color="primary">
Reset Password Sekarang
</x-mail::button>

Jika Anda tidak meminta reset password, abaikan saja email ini.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>