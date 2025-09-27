<?php

namespace App\Http\Controllers;

use App\Models\SiswaModel;
use Illuminate\Http\Request;

class PindahKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => '',

        ];

        return view('private.data.pindah_kelas.view')->with($data);
    }

    public function show(Request $request)
    {
        $kelas = $request->kelas;
        $tahun = session('tahun_login');

        $siswa = SiswaModel::where('kelas', $kelas)
            ->where('status_siswa', 'aktif') // hanya siswa aktif
            ->where('tahun_masuk', $tahun)
            ->get();

        // rekap per kelas
        $rekapPerKelas = SiswaModel::select('kelas')
            ->where('status_siswa', 'aktif')
            ->where('tahun_masuk', $tahun)
            ->groupBy('kelas')
            ->selectRaw('kelas, count(*) as total')
            ->pluck('total', 'kelas')
            ->toArray();

        $jumlahSiswa = $siswa->count();

        return view('private.data.pindah_kelas.show', compact(
            'siswa',
            'kelas',
            'rekapPerKelas',
            'jumlahSiswa'
        ));
    }

    public function pindahkan(Request $request)
    {
        $ids = $request->id_siswa ?? [];
        $kelasTujuan = $request->kelas_tujuan; // null kalau kelas XII

        if (empty($ids)) {
            return response()->json(['error' => 'Tidak ada siswa yang dipilih']);
        }

        $updated = 0;
        $kelasXII = 0;
        $naikKelas = 0;

        foreach ($ids as $id) {
            $siswa = \App\Models\SiswaModel::find($id);
            if ($siswa) {
                if ($siswa->kelas === 'XII') {
                    // Jika kelas XII, klik pindah = jadi lulus
                    $siswa->status_siswa = 'lulus';
                    $kelasXII++;
                } else {
                    // Selain XII, naik kelas
                    $siswa->kelas = $kelasTujuan;
                    $siswa->status_siswa = 'aktif';
                    $naikKelas++;
                }
                $siswa->save();
                $updated++;
            }
        }

        // Buat pesan dinamis
        $msg = "Berhasil memproses {$updated} siswa.";
        if ($naikKelas > 0) {
            $msg .= " {$naikKelas} siswa naik kelas.";
        }
        if ($kelasXII > 0) {
            $msg .= " {$kelasXII} siswa lulus.";
        }

        return response()->json([
            'success' => $msg,
            'myReload' => 'pindahkelasdata',
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
