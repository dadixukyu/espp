<?php

namespace App\Http\Controllers;

use App\Models\ParBiayaModel;
use App\Models\ParKelasModel;
use App\Models\ParSPPModel;
use App\Models\ParTahunAjaranModel;
use App\Models\PendaftaranModel;
use App\Models\SiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => '',

        ];

        return view('private.data.pendaftaran.view')->with($data);
    }

    /**
     * Tampilkan detail pendaftaran berdasarkan ID
     */
    public function show(Request $r)
    {
        if ($r->ajax()) {

            // Ambil tahun dari session, default tahun sekarang
            $tahunLogin = session('tahun_login', date('Y'));

            // Ambil data pendaftaran sesuai tahun login
            $pendaftaran = PendaftaranModel::with('spp')
                ->where('tahun', $tahunLogin) // filter berdasarkan kolom 'tahun'
                ->get();

            // Hitung jumlah pendaftar total
            $jumlahPendaftar = $pendaftaran->count();

            // Hitung jumlah pendaftar per jurusan
            $jumlahPerJurusan = PendaftaranModel::select('jurusan', DB::raw('count(*) as total'))
                ->where('tahun', $tahunLogin) // filter berdasarkan kolom 'tahun'
                ->groupBy('jurusan')
                ->get();

            // Kirim data ke view
            $data = [
                'result' => $pendaftaran,
                'jumlahPendaftar' => $jumlahPendaftar,
                'jumlahPerJurusan' => $jumlahPerJurusan,
            ];

            return view('private.data.pendaftaran.show', $data);

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

            // Ambil tahun ajaran sesuai tahun login
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

            return view('private.data.pendaftaran.formadd', $data);
        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }

    public function store(Request $r)
    {
        // VALIDASI
        $validator = Validator::make($r->all(), [
            'id_tahun' => 'required|exists:par_tahun_ajaran,id_tahun',
            'nisn' => 'required|unique:a_siswa,nisn|unique:a_pendaftaran,nisn',
            'tgl_daftar' => 'required|date',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'status_siswa' => 'required|in:aktif',
            'tahun_masuk' => 'required|digits:4|integer',
            'asal_sekolah' => 'required|string|max:150',
            'kelas' => 'required|exists:par_kelas,id_kelas',
            'jurusan' => 'required|exists:par_kelas,id_kelas',
            'email' => 'nullable|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'kategori_biaya' => 'required|exists:par_spp,tahun',
            'pengurangan_biaya' => 'nullable|string|max:50',
            'biaya_pendaftaran' => 'nullable|string|max:50',
        ], [
            'id_tahun.required' => 'Tahun ajaran wajib dipilih',
            'id_tahun.exists' => 'Tahun ajaran tidak valid',
            'nisn.required' => 'NISN wajib diisi',
            'nisn.unique' => 'NISN sudah terdaftar',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date' => 'Tanggal lahir harus format tanggal (YYYY-MM-DD)',
            'agama.required' => 'Agama wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'status_siswa.required' => 'Status siswa wajib dipilih',
            'tahun_masuk.required' => 'Tahun masuk wajib diisi',
            'tahun_masuk.digits' => 'Tahun masuk harus terdiri dari 4 digit',
            'asal_sekolah.required' => 'Asal sekolah wajib diisi',
            'kelas.required' => 'Kelas wajib dipilih',
            'kelas.exists' => 'Kelas tidak valid',
            'jurusan.required' => 'Jurusan wajib dipilih',
            'jurusan.exists' => 'Jurusan tidak valid',
            'kategori_biaya.required' => 'Kategori SPP wajib dipilih',
            'kategori_biaya.exists' => 'Kategori SPP tidak valid',
            'biaya_pendaftaran.required' => 'Biaya Pendaftaran wajib dipilih',
            'tgl_daftar.required' => 'Tanggal daftar wajib dipilih',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // AMBIL NOMINAL BIAYA SPP
        $spp = ParSppModel::where('tahun', $r->kategori_biaya)->firstOrFail();

        $biaya = ParBiayaModel::where('tahun', $r->biaya_lain)->firstOrFail();

        // Ambil data kelas & jurusan
        $kelas = ParKelasModel::findOrFail($r->kelas);
        $jurusan = ParKelasModel::findOrFail($r->jurusan);

        // DATA UNTUK a_pendaftaran
        $dataPendaftaran = [
            'id_tahun' => $r->id_tahun,
            // 'kd_data'         => 1,
            'nisn' => $r->nisn,
            'nama_lengkap' => $r->nama_lengkap,
            'jenis_kelamin' => $r->jenis_kelamin,
            'tempat_lahir' => $r->tempat_lahir,
            'tanggal_lahir' => $r->tanggal_lahir,
            'agama' => $r->agama,
            'alamat' => $r->alamat,
            'tahun_masuk' => $r->tahun_masuk,
            'asal_sekolah' => $r->asal_sekolah,
            'kelas' => $kelas->kelas,
            'jurusan' => $jurusan->jurusan,
            'status_siswa' => $r->status_siswa,
            'email' => $r->email,
            'no_hp' => $r->no_hp,
            'tgl_daftar' => $r->tgl_daftar,
            'kategori_biaya' => $spp->nominal,
            'biaya_lain' => $biaya->tahun,

            'pengurangan_biaya' => $r->pengurangan_biaya
                                    ? preg_replace('/[^0-9]/', '', $r->pengurangan_biaya)
                                    : 0,
            // 'biaya_pendaftaran' => $r->biaya_pendaftaran
            //                         ? preg_replace('/[^0-9]/', '', $r->biaya_pendaftaran)
            //                         : 0,
            'tahun' => Session::get('tahun_login'),
        ];

        // DATA UNTUK a_siswa
        $dataSiswa = [
            'id_tahun' => $r->id_tahun,
            'kd_data' => 1,
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
            'email' => $r->email,
            'no_hp' => $r->no_hp,
            'kategori_biaya' => $spp->nominal,
            'biaya_lain' => $biaya->tahun,

            'pengurangan_biaya' => $r->pengurangan_biaya
                                    ? preg_replace('/[^0-9]/', '', $r->pengurangan_biaya)
                                    : 0,
        ];

        try {
            DB::transaction(function () use ($dataPendaftaran, $dataSiswa) {
                PendaftaranModel::create($dataPendaftaran);
                SiswaModel::create($dataSiswa);
            });

            return response()->json([
                'success' => 'Data berhasil disimpan ke tabel Pendaftaran & Siswa',
                'myReload' => 'pendaftarandata',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function edit(string $id)
    {
        if (request()->ajax()) {

            $tahun = session('tahun_login'); // ambil tahun login

            // Ambil data pendaftaran beserta relasi siswa
            $row = PendaftaranModel::with('siswa')->where('id_pendaftaran', $id)->firstOrFail();

            // Ambil SPP sesuai tahun login
            $parSpp = ParSppModel::where('tahun', $tahun)->first();

            // Ambil semua biaya sesuai tahun login
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

            return view('private.data.pendaftaran.formedit', [
                'id' => $id,
                'row' => $row,
                'title_form' => 'FORM EDIT DATA PENDAFTARAN',
                'parSpp' => $parSpp,
                'parBiaya' => $parBiaya,
                'parKelas' => $parKelas,
                'parJurusan' => $parJurusan,
                'tahunAjaran' => $tahunAjaran,
            ]);
        } else {
            exit('Maaf, request tidak dapat diproses');
        }
    }

    public function update(Request $r, $id)
    {
        // VALIDASI
        $validator = Validator::make($r->all(), [
            'id_tahun' => 'required|exists:par_tahun_ajaran,id_tahun',
            'nisn' => 'required|string|max:50',
            'tgl_daftar' => 'required|date',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'status_siswa' => 'required|in:aktif',
            'tahun_masuk' => 'required|digits:4|integer',
            'asal_sekolah' => 'required|string|max:150',
            'kelas' => 'required|exists:par_kelas,id_kelas',
            'jurusan' => 'required|exists:par_kelas,id_kelas',
            'email' => 'nullable|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'kategori_biaya' => 'required|exists:par_spp,tahun',
            'pengurangan_biaya' => 'nullable|string|max:50',
            'biaya_pendaftaran' => 'nullable|string|max:50',
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

        try {
            DB::transaction(function () use ($r, $id) {
                // Ambil data pendaftaran dan siswa
                $pendaftaran = PendaftaranModel::findOrFail($id); // dari route
                $siswa = SiswaModel::findOrFail($r->id_siswa); // dari hidden input
                $biaya = ParBiayaModel::where('tahun', $r->biaya_lain)->firstOrFail();
                // Ambil referensi spp, kelas, jurusan
                $spp = ParSppModel::where('tahun', $r->kategori_biaya)->firstOrFail();
                $kelas = ParKelasModel::findOrFail($r->kelas);
                $jurusan = ParKelasModel::findOrFail($r->jurusan);

                // Data untuk update tabel pendaftaran
                $dataPendaftaran = [
                    'id_tahun' => $r->id_tahun,
                    'nisn' => $r->nisn,
                    'nama_lengkap' => $r->nama_lengkap,
                    'jenis_kelamin' => $r->jenis_kelamin,
                    'tempat_lahir' => $r->tempat_lahir,
                    'tanggal_lahir' => $r->tanggal_lahir,
                    'agama' => $r->agama,
                    'alamat' => $r->alamat,
                    'tahun_masuk' => $r->tahun_masuk,
                    'asal_sekolah' => $r->asal_sekolah,
                    'kelas' => $kelas->kelas,
                    'jurusan' => $jurusan->jurusan,
                    'status_siswa' => $r->status_siswa,
                    'email' => $r->email,
                    'no_hp' => $r->no_hp,
                    'tgl_daftar' => $r->tgl_daftar,
                    'kategori_biaya' => $spp->nominal,
                    'biaya_lain' => $biaya->tahun,
                    'pengurangan_biaya' => $r->pengurangan_biaya
                                            ? preg_replace('/[^0-9]/', '', $r->pengurangan_biaya)
                                            : 0,
                    // 'biaya_pendaftaran' => $r->biaya_pendaftaran
                    //                         ? preg_replace('/[^0-9]/', '', $r->biaya_pendaftaran)
                    //                         : 0,
                    'tahun' => Session::get('tahun_login'),
                ];

                // Data untuk update tabel siswa
                $dataSiswa = [
                    'id_tahun' => $r->id_tahun,
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
                    'email' => $r->email,
                    'no_hp' => $r->no_hp,
                    'kategori_biaya' => $spp->nominal,
                    'biaya_lain' => $biaya->tahun,
                    'pengurangan_biaya' => $r->pengurangan_biaya
                                            ? preg_replace('/[^0-9]/', '', $r->pengurangan_biaya)
                                            : 0,
                ];

                // Update tabel
                $pendaftaran->update($dataPendaftaran);
                $siswa->update($dataSiswa);
            });

            return response()->json([
                'success' => 'Data berhasil diperbarui di tabel Pendaftaran & Siswa',
                'myReload' => 'pendaftarandata',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat update data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        if (request()->ajax()) {

            $pendaftaran = PendaftaranModel::find($id);

            if ($pendaftaran) {

                // Cek apakah ada tagihan lain
                if ($pendaftaran->tagihanLain()->count() > 0) {
                    return response()->json([
                        'error' => 'Data Pendaftaran tidak bisa dihapus karena sudah ada Tagihan Lain terkait.',
                    ]);
                }

                // Hapus siswa terkait
                if ($pendaftaran->siswa) {
                    $pendaftaran->siswa->delete(); // pastikan SiswaModel pakai primary key yang benar
                }

                // Hapus pendaftaran
                $pendaftaran->delete();

                return response()->json([
                    'success' => 'Data Pendaftaran dan Siswa berhasil dihapus',
                    'myReload' => 'pendaftarandata',
                ]);
            } else {
                return response()->json([
                    'error' => 'Data tidak ditemukan',
                ]);
            }

        } else {
            exit('Maaf Tidak Dapat diproses...');
        }
    }
}
