<?php

namespace App\Http\Controllers;

use App\Mail\NotifKenaikanJabatan;
use App\Models\EmailLog;
use App\Models\Pejabat;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PejabatController extends Controller
{
    /**
     * TAMPILKAN SEMUA DATA
     */
    public function index()
    {
        try {
            $pejabat = Pejabat::all();

            // Skenario 1: Data kosong di database (Status 404)
            if ($pejabat->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pejabat belum tersedia di database.',
                    'data' => []
                ], 404);
            }

            $data = $pejabat->map(function ($p) {
                $tmt = Carbon::parse($p->tmt_pangkat);
                $sekarang = Carbon::now();

                // Menggunakan diff untuk mendapatkan objek DateInterval
                $diff = $tmt->diff($sekarang);

                $tahun = $diff->y; // Mengambil angka tahun
                $bulan = $diff->m; // Mengambil angka bulan
                $hari = $diff->d;  // Mengambil angka hari

                $lamapangkat = "{$tahun} Tahun {$bulan} Bulan {$hari} Hari";

                return [
                    'id' => $p->id,
                    'nip' => $p->nip,
                    'nama' => $p->nama,
                    'email' => $p->email,
                    'pangkat' => $p->pangkat_sekarang,
                    'tmt' => $p->tmt_pangkat,
                    'lama_pangkat' => $lamapangkat,
                    'rundown' => $p->rundown,
                    'perlu_kenaikan' => $tahun >= 4,
                    'pesan' => $tahun >= 4 ? "Bersiap untuk kenaikan Pangkat!" : "Masa Pangkat aman."
                ];
            });

            // Skenario 2: Berhasil diproses (Status 200)
            return response()->json([
                'success' => true,
                'message' => 'Daftar Masa Pangkat Pejabat berhasil diambil.',
                'data' => $data
            ], 200);

        } catch (Exception $e) {
            // Mencatat error ke file log laravel.log agar mudah di-debug
            Log::error('Error get data pejabat: ' . $e->getMessage());

            // Skenario 3: Terjadi error pada sistem/server (Status 500)
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server saat memproses data.',
                // Opsional: Anda bisa mengirimkan $e->getMessage() untuk mempermudah debug saat development, 
                // tapi sebaiknya disembunyikan saat production.
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * SIMPAN DATA BARU
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Menggunakan min:18 untuk minimal 18 karakter (tipe string)
            // 'nip' => 'required|string|min:18|unique:pejabats,nip',

            // Alternatif opsi yang lebih ketat: Wajib angka dan tepat 18 digit
            'nip' => 'required|numeric|digits:18|unique:pejabats,nip',
            'email' => 'required|string|unique:pejabats,email',
            'nama' => 'required|string',
            'pangkat_sekarang' => 'required|string',
            'tmt_pangkat' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pejabat = Pejabat::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Pejabat berhasil ditambahkan',
            'data' => $pejabat
        ], 201);
    }

    /**
     * TAMPILKAN SATU DATA SPESIFIK
     */
    public function show($id)
    {
        $pejabat = Pejabat::find($id);

        if (!$pejabat) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $tmt = Carbon::parse($pejabat->tmt_pangkat);
        $diffYears = $tmt->diffInYears(now());

        return response()->json([
            'success' => true,
            'data' => array_merge($pejabat->toArray(), [
                'lama_pangkat' => $diffYears . " Tahun",
                'perlu_kenaikan' => $diffYears >= 4,
            ])
        ]);
    }

    /**
     * UPDATE DATA
     */
    public function update(Request $request, $id)
    {
        $pejabat = Pejabat::find($id);

        if (!$pejabat) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            // Tambahkan min:18 atau digits:18 di sini agar konsisten dengan saat create
            'nip' => 'sometimes|numeric|digits:18|unique:pejabats,nip,' . $id,
            'email' => 'sometimes|string|unique:pejabats,email',
            'nama' => 'sometimes|string',
            'pangkat_sekarang' => 'sometimes|string',
            'tmt_pangkat' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pejabat->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Pejabat berhasil diperbarui',
            'data' => $pejabat
        ]);
    }

    /**
     * HAPUS DATA
     */
    public function destroy($id)
    {
        $pejabat = Pejabat::find($id);

        if (!$pejabat) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $pejabat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Pejabat berhasil dihapus'
        ]);
    }


    public function sendManualWarning(Request $request, $id)
    {
        // 1. Cari pejabat berdasarkan ID
        $pejabat = Pejabat::find($id);

        if (!$pejabat) {
            return response()->json([
                'success' => false,
                'message' => 'Data pejabat tidak ditemukan.'
            ], 404);
        }

        if (!$pejabat->email) {
            return response()->json([
                'success' => false,
                'message' => 'Pejabat ini tidak memiliki alamat email.'
            ], 422);
        }

        try {
            // 2. Kirim email ke email pejabat tersebut
            Mail::to($pejabat->email)->send(new NotifKenaikanJabatan($pejabat));

            // 3. Catat ke log dengan keterangan dikirim oleh Admin
            EmailLog::create([
                'pejabat_id' => $pejabat->id,
                'email_tujuan' => $pejabat->email,
                'status' => 'Berhasil',
                'keterangan' => 'Dikirim secara manual oleh Admin: ' . auth()->user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email peringatan berhasil dikirim ke ' . $pejabat->nama
            ]);

        } catch (\Exception $e) {
            EmailLog::create([
                'pejabat_id' => $pejabat->id,
                'email_tujuan' => $pejabat->email,
                'status' => 'Gagal',
                'keterangan' => 'Gagal kirim manual: ' . $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email. ' . $e->getMessage()
            ], 500);
        }
    }
}