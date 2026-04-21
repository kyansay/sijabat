<x-mail::message>
    # Halo Bapak/Ibu {{ $pejabat->nama }},

    Melalui email ini kami informasikan bahwa masa jabatan Anda sebagai **{{ $pejabat->jabatan_sekarang }}** telah
    memasuki masa 4 tahun (TMT: {{ $pejabat->tmt_jabatan }}).

    Silakan persiapkan berkas dan dokumen yang diperlukan untuk proses pertimbangan kenaikan jabatan Anda selanjutnya.

    Terima kasih,<br>
    Bagian Kepegawaian {{ config('app.name') }}
</x-mail::message>
