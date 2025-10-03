<?php

namespace App\Http\Controllers;

use App\Models\ParBiayaModel;
use App\Models\ParKelasModel;
use App\Models\ParSPPModel;
use App\Models\ParTahunAjaranModel;
use App\Models\SiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = [
            'title' => '',

        ];

        return view('private.data.siswa.view')->with($data);
    }

    public function show(Request $r)
    {
        if ($r->ajax()) {
            $result = SiswaModel::with('spp')
                ->where('kd_data', 1)
                ->get();

            // Total siswa aktif
            $totalSiswaAktif = $result->where('status_siswa', 'aktif')->count();

            // Jumlah siswa per kelas X, XI, XII
            $jumlahSiswaPerKelas = $result->where('status_siswa', 'aktif')
                ->groupBy('kelas')
                ->map(function ($kelas) {
                    return $kelas->count();
                });

            $jumlahSiswaX = $jumlahSiswaPerKelas->get('X') ?? 0;
            $jumlahSiswaXI = $jumlahSiswaPerKelas->get('XI') ?? 0;
            $jumlahSiswaXII = $jumlahSiswaPerKelas->get('XII') ?? 0;

            return view('private.data.siswa.show', compact(
                'result',
                'totalSiswaAktif',
                'jumlahSiswaX',
                'jumlahSiswaXI',
                'jumlahSiswaXII'
            ));
        } else {
            abort(403, 'Akses tidak diperbolehkan');
        }
    }

    public function create()
    {
        if (request()->ajax()) {
            // Ambil tahun login dari session
            $tahun = session('tahun_login');

            // Ambil SPP sesuai tahun login
            $parSpp = ParSppModel::where('tahun', $tahun)->first();

            // Ambil semua biaya lain sesuai tahun login
            $parBiaya = ParBiayaModel::where('tahun', $tahun)->get();

            // Ambil semua kelas
            $parKelas = ParKelasModel::orderBy('kelas', 'asc')
                ->get()
                ->unique('kelas')
                ->values();

            // Ambil semua jurusan
            $parJurusan = ParKelasModel::orderBy('jurusan', 'asc')
                ->get()
                ->unique('jurusan')
                ->values();

            // Ambil tahun ajaran sesuai tahun login yang aktif
            $tahunAjaran = ParTahunAjaranModel::where('tahun', $tahun)
                ->aktif()
                ->get();

            $data = [
                'title_form' => 'FORM INPUT DATA SISWA BARU',
                'parSpp' => $parSpp,
                'parBiaya' => $parBiaya,
                'parKelas' => $parKelas,
                'parJurusan' => $parJurusan,
                'tahunAjaran' => $tahunAjaran,
            ];

            return view('private.data.siswa.formadd', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'id_tahun' => 'required|exists:par_tahun_ajaran,id_tahun',
            'nisn' => 'required|unique:a_siswa,nisn',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'tahun_masuk' => 'required|digits:4|integer',

            'kelas' => 'required|exists:par_kelas,id_kelas',
            'jurusan' => 'required|exists:par_kelas,id_kelas',
            'status_siswa' => 'required|in:aktif,lulus,pindah,keluar',
            'nama_ayah' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'nama_wali' => 'nullable|string|max:100',
            'pekerjaan_wali' => 'nullable|string|max:100',
            'no_hp_wali' => 'nullable|string|max:20',
            'alamat_wali' => 'nullable|string',
            'email' => 'nullable|email|max:100',
            'no_hp' => 'nullable|string|max:20',

            'kategori_biaya' => 'required|exists:par_spp,tahun',
            'pengurangan_biaya' => 'nullable|string|max:50', // opsional
        ], [
            'id_tahun.required' => 'Tahun ajaran wajib dipilih',
            'id_tahun.exists' => 'Tahun ajaran tidak valid',
            'nisn.required' => 'NISN wajib diisi',
            'nisn.unique' => 'NISN sudah terdaftar, silakan gunakan NISN lain',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date' => 'Tanggal lahir harus format tanggal (YYYY-MM-DD)',
            'agama.required' => 'Agama wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'tahun_masuk.required' => 'Tahun masuk wajib diisi',
            'tahun_masuk.digits' => 'Tahun masuk harus terdiri dari 4 digit',

            'kelas.required' => 'Kelas wajib dipilih',
            'kelas.exists' => 'Kelas tidak valid',
            'jurusan.required' => 'Jurusan wajib dipilih',
            'jurusan.exists' => 'Jurusan tidak valid',
            'status_siswa.required' => 'Status siswa wajib dipilih',
            'kategori_biaya.required' => 'Kategori SPP wajib dipilih',
            'kategori_biaya.exists' => 'Kategori SPP tidak valid',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Ambil data kategori biaya
        $spp = ParSppModel::where('tahun', $r->kategori_biaya)->firstOrFail();

        // Ambil data kelas & jurusan
        $kelas = ParKelasModel::findOrFail($r->kelas);
        $jurusan = ParKelasModel::findOrFail($r->jurusan);

        // Simpan data ke database
        $post = SiswaModel::create([
            'id_tahun' => $r->id_tahun,
            'kd_data' => 1, // selalu 1 saat store
            'nisn' => $r->nisn,
            'nama_lengkap' => $r->nama_lengkap,
            'jenis_kelamin' => $r->jenis_kelamin,
            'tempat_lahir' => $r->tempat_lahir,
            'tanggal_lahir' => $r->tanggal_lahir,
            'agama' => $r->agama,
            'alamat' => $r->alamat,
            'tahun_masuk' => $r->tahun_masuk,
            'kelas' => $kelas->kelas,
            'jurusan' => $jurusan->jurusan,
            'status_siswa' => $r->status_siswa,
            'nama_ayah' => $r->nama_ayah,
            'pekerjaan_ayah' => $r->pekerjaan_ayah,
            'nama_ibu' => $r->nama_ibu,
            'pekerjaan_ibu' => $r->pekerjaan_ibu,
            'nama_wali' => $r->nama_wali,
            'pekerjaan_wali' => $r->pekerjaan_wali,
            'no_hp_wali' => $r->no_hp_wali,
            'alamat_wali' => $r->alamat_wali,
            'email' => $r->email,
            'no_hp' => $r->no_hp,
            'kategori_biaya' => $spp->nominal,
            'pengurangan_biaya' => preg_replace('/[^0-9]/', '', $r->pengurangan_biaya), // hilangkan Rp dan titik
        ]);

        return response()->json([
            'success' => 'Data siswa berhasil disimpan',
            'myReload' => 'siswadata',
        ]);
    }

    public function edit(string $id)
    {
        if (request()->ajax()) {

            $row = SiswaModel::findOrFail($id);

            // Ambil tahun login dari session
            $tahun = session('tahun_login');

            // Parameter input menyesuaikan tahun login
            $parSpp = ParSppModel::where('tahun', $tahun)->first();
            $parBiaya = ParBiayaModel::where('tahun', $tahun)->get();
            $tahunAjaran = ParTahunAjaranModel::where('tahun', $tahun)->aktif()->get();

            $parKelas = ParKelasModel::orderBy('kelas', 'asc')->get()->unique('kelas')->values();
            $parJurusan = ParKelasModel::orderBy('jurusan', 'asc')->get()->unique('jurusan')->values();

            return view('private.data.siswa.formedit', [
                'id' => $id,
                'row' => $row,
                'title_form' => 'FORM EDIT DATA SISWA',
                'parSpp' => $parSpp,
                'parBiaya' => $parBiaya,
                'tahunAjaran' => $tahunAjaran,
                'parKelas' => $parKelas,
                'parJurusan' => $parJurusan,
            ]);

        } else {
            exit('Maaf, request tidak dapat diproses');
        }
    }

    public function update(Request $r, $id)
    {
        $row = SiswaModel::findOrFail($id);

        $validator = Validator::make($r->all(), [
            'nisn' => 'required|unique:a_siswa,nisn,'.$id.',id_siswa',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'tahun_masuk' => 'required|digits:4|integer',
            'kelas' => 'required|exists:par_kelas,id_kelas',
            'jurusan' => 'required|exists:par_kelas,id_kelas',
            'status_siswa' => 'required|in:aktif,lulus,pindah,keluar',
            'nama_ayah' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'nama_wali' => 'nullable|string|max:100',
            'pekerjaan_wali' => 'nullable|string|max:100',
            'no_hp_wali' => 'nullable|string|max:20',
            'alamat_wali' => 'nullable|string',
            'email' => 'nullable|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'kategori_biaya' => 'required|exists:par_spp,tahun',
        ], [
            'nisn.required' => 'NISN wajib diisi',
            'nisn.unique' => 'NISN sudah terdaftar, silakan gunakan NISN lain',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date' => 'Tanggal lahir harus format tanggal (YYYY-MM-DD)',
            'agama.required' => 'Agama wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'tahun_masuk.required' => 'Tahun masuk wajib diisi',
            'tahun_masuk.digits' => 'Tahun masuk harus terdiri dari 4 digit',
            'kelas.required' => 'Kelas wajib dipilih',
            'jurusan.required' => 'Jurusan wajib dipilih',
            'status_siswa.required' => 'Status siswa wajib dipilih',
            'kategori_biaya.required' => 'Kategori SPP wajib dipilih',
            'kategori_biaya.exists' => 'Kategori SPP tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Ambil nominal SPP
        $spp = ParSppModel::where('tahun', $r->kategori_biaya)->firstOrFail();

        // Ambil data kelas & jurusan
        $kelas = ParKelasModel::findOrFail($r->kelas);
        $jurusan = ParKelasModel::findOrFail($r->jurusan);

        // Update data siswa
        $row->update([
            'id_tahun' => $r->id_tahun,
            'kd_data' => 1, // selalu 1 saat store
            'nama_lengkap' => $r->nama_lengkap,
            'jenis_kelamin' => $r->jenis_kelamin,
            'tempat_lahir' => $r->tempat_lahir,
            'tanggal_lahir' => $r->tanggal_lahir,
            'agama' => $r->agama,
            'alamat' => $r->alamat,
            'tahun_masuk' => $r->tahun_masuk,
            'kelas' => $kelas->kelas,
            'jurusan' => $jurusan->jurusan,
            'status_siswa' => $r->status_siswa,
            'nama_ayah' => $r->nama_ayah,
            'pekerjaan_ayah' => $r->pekerjaan_ayah,
            'nama_ibu' => $r->nama_ibu,
            'pekerjaan_ibu' => $r->pekerjaan_ibu,
            'nama_wali' => $r->nama_wali,
            'pekerjaan_wali' => $r->pekerjaan_wali,
            'no_hp_wali' => $r->no_hp_wali,
            'alamat_wali' => $r->alamat_wali,
            'email' => $r->email,
            'no_hp' => $r->no_hp,
            'kategori_biaya' => $spp->nominal,
            'pengurangan_biaya' => preg_replace('/[^0-9]/', '', $r->pengurangan_biaya),
        ]);

        // Jika ada Biaya Lain
        if ($r->has('biaya_lain')) {
            // Simpan ke pivot / relasi biaya lain sesuai struktur DB
            // Contoh: $row->biaya_lain()->sync($r->biaya_lain);
        }

        return response()->json([
            'success' => 'Data siswa berhasil diperbarui',
            'myReload' => 'siswadata',
        ]);
    }

    public function destroy($id)
    {
        if (request()->ajax()) {

            // $post = SiswaModel::where('id_siswa', $id)->delete();
            $post = SiswaModel::findOrFail($id);
            $post->update(['kd_data' => null]); // hanya set null, data tetap ada

            if ($post) {
                return response()->json([
                    'success' => 'Data Siswa berhasil dihapus',
                    'myReload' => 'siswadata',
                ]);
            }
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }
}
