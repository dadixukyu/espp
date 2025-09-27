<?php

namespace App\Http\Controllers;

use App\Models\SiswaModel;
use Illuminate\Http\Request;

class PenampungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => '',

        ];

        return view('private.data.penampung.view')->with($data);
    }

    public function show(Request $request)
    {
        $status = $request->kelas; // ini sesuai dropdown "Pilih Status"

        // Query siswa sesuai filter status, tanpa membatasi tahun
        $query = SiswaModel::whereIn('status_siswa', ['aktif', 'lulus', 'pindah', 'keluar']);

        if ($status) {
            $query->where('status_siswa', $status);
        }

        $siswa = $query->orderBy('kelas')->get();

        // Rekap per kelas (hanya siswa aktif, tanpa filter tahun)
        $rekapPerKelas = SiswaModel::where('status_siswa', 'aktif')
            ->groupBy('kelas')
            ->selectRaw('kelas, count(*) as total')
            ->pluck('total', 'kelas')
            ->toArray();

        // Rekap per status (aktif, lulus, pindah, keluar) tanpa filter tahun
        $rekapPerStatus = SiswaModel::whereIn('status_siswa', ['aktif', 'lulus', 'pindah', 'keluar'])
            ->groupBy('status_siswa')
            ->selectRaw('status_siswa, count(*) as total')
            ->pluck('total', 'status_siswa')
            ->toArray();

        $jumlahSiswa = $siswa->count();

        // Kirim data ke view partial untuk AJAX
        return view('private.data.penampung.show', compact(
            'siswa',
            'status',
            'rekapPerKelas',
            'rekapPerStatus',
            'jumlahSiswa'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
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
