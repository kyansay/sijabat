<x-mail::message>
<div style="text-align: center;">
<img src="{{ $message->embed(public_path('images/logo-imigrasi.png')) }}" width="100" alt="Logo Imigrasi">
</div>

# Pemberitahuan Masa Peninjauan Pangkat/Jabatan

**Yth. Bapak/Ibu {{ $pejabat->nama }},**

Berdasarkan basis data kepegawaian kami, masa pangkat/jabatan Bapak/Ibu saat ini telah memasuki periode peninjauan (genap 4 tahun). Berikut adalah rincian data kepegawaian Bapak/Ibu:

* **Jabatan Saat Ini** : {{ $pejabat->jabatan_sekarang }}
* **Terhitung Mulai Tanggal Pelantikan** : {{ \Carbon\Carbon::parse($pejabat->tmt_jabatan)->translatedFormat('d F Y') }}

Sehubungan dengan hal tersebut, kami mengimbau Bapak/Ibu untuk mulai mempersiapkan kelengkapan dokumen dan persyaratan administratif yang diperlukan untuk proses usulan kenaikan pangkat/jabatan periode berikutnya.

Apabila terdapat ketidaksesuaian data atau memerlukan informasi lebih lanjut mengenai daftar persyaratan administrasi, silakan berkoordinasi dengan Subbagian Tata Usaha / Urusan Kepegawaian.

Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.

Hormat kami,

**Subbagian Kepegawaian**

Kantor Imigrasi Kelas II TPI Mimika

{{ config('app.name') }}
</x-mail::message>