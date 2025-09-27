<?php

use App\Http\Controllers\KasController;
use App\Http\Controllers\LaporanKasController;
use App\Http\Controllers\LaporanPendaftaranController;
use App\Http\Controllers\LaporanSPPController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ParBiayaController;
use App\Http\Controllers\ParKelasController;
use App\Http\Controllers\ParSPPController;
use App\Http\Controllers\ParTahunAjaranController;
use App\Http\Controllers\PenampungController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengaturanAkunController;
use App\Http\Controllers\PindahKelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TagihanLainController;
use App\Http\Controllers\TagihanSPPController;
use App\Http\Controllers\UserDataController;
use Illuminate\Support\Facades\Route;

// form login
Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('login/proses', 'proses');
    Route::get('cekdata', 'cekdata')->name('cekdata');
    Route::get('logout', 'logout')->name('logout');
});

// grup dengan middleware auth
Route::group(['middleware' => ['auth']], function () {

    // grup dengan middleware cekUserLoginMultiple
    Route::group(['middleware' => ['cekUserLoginMultiple:1,2,3']], function () {

        Route::get('/', [MainController::class, 'index'])->name('main');

        // user
        Route::get('userdata/username/{id}', [UserDataController::class, 'username'])->name('userdata.username');
        Route::get('userdata/resetpassword/{id}', [UserDataController::class, 'resetpassword'])->name('userdata.resetpassword');
        Route::put('userdata/updateusername/{id}', [UserDataController::class, 'updateusername'])->name('userdata.updateusername');
        Route::put('userdata/updatepassword/{id}', [UserDataController::class, 'updatepassword'])->name('userdata.updatepassword');

        Route::resource('userdata', UserDataController::class);

        // pengaturan akun
        Route::put('pengaturanakun/updatepassword/{id}', [PengaturanAkunController::class, 'updatepassword'])->name('pengaturanakun.updatepassword');
        Route::put('pengaturanakun/updateemail', [PengaturanAkunController::class, 'updateemail'])->name('pengaturanakun.updateemail');
        Route::get('pengaturanakun', [PengaturanAkunController::class, 'index'])->name('pengaturanakun.index');
    });

    Route::group(['middleware' => ['cekUserLoginMultiple:1,3']], function () {

        // siswa
        Route::resource('siswadata', SiswaController::class);

        // pendaftaran
        Route::resource('pendaftarandata', PendaftaranController::class);

        // parameter
        Route::resource('parsppdata', ParSPPController::class);
        Route::resource('parbiayadata', ParBiayaController::class);
        Route::resource('partahundata', ParTahunAjaranController::class);
        Route::resource('parkelasdata', ParKelasController::class);

        // tagihan spp

        Route::get('tagihan_sppdata/create_tagihan_spp/{id}', [TagihanSPPController::class, 'create_tagihan_spp'])->name('tagihan_sppdata.create_tagihan_spp');
        Route::post('tagihan_sppdata/store_tagihan_spp', [TagihanSPPController::class, 'store_tagihan_spp'])->name('tagihan_sppdata.store_tagihan_spp');
        Route::get('tagihan_sppdata/show_tagihan_spp/{id}', [TagihanSPPController::class, 'show_tagihan_spp'])->name('tagihan_sppdata.show_tagihan_spp');
        Route::resource('tagihan_sppdata', TagihanSPPController::class);

        // tagihan lainnya

        Route::get('tagihanlaindata/create/{id}', [TagihanLainController::class, 'create'])
            ->name('tagihanlaindata.create');

        Route::get('tagihanlaindata/show_detail/{id}', [TagihanLainController::class, 'show_detail'])
            ->name('tagihanlaindata.show_detail');

        Route::resource('tagihanlaindata', TagihanLainController::class)
            ->except(['create']); // kecuali create

        // siswa
        Route::resource('kasdata', KasController::class);

        // Pindah Kelas
        Route::post('pindahkelasdata/pindahkan', [PindahKelasController::class, 'pindahkan'])
            ->name('pindahkelasdata.pindahkan');
        Route::resource('pindahkelasdata', PindahKelasController::class);

        // Penamoung siswa yang lulus, keluar, dan pindah
        Route::resource('penampungdata', PenampungController::class);
    });

    Route::group(['middleware' => ['cekUserLoginMultiple:1,2,3']], function () {
        // laporan Pendaftaran
        Route::get('laporanpendaftarandata/cetak_pendaftaran_pdf',
            [LaporanPendaftaranController::class, 'cetak_pendaftaran_pdf']
        )->name('laporanpendaftarandata.cetak_pendaftaran_pdf');

        Route::get('laporanpendaftarandata/show',
            [LaporanPendaftaranController::class, 'show']
        );

        Route::resource('laporanpendaftarandata', LaporanPendaftaranController::class);

        // laporan SPP
        Route::get('laporansppdata/cetak_spp_pdf',
            [LaporanSPPController::class, 'cetak_spp_pdf']
        )->name('laporansppdata.cetak_spp_pdf');

        Route::get('laporansppdata/show',
            [LaporanSPPController::class, 'show']
        );

        Route::resource('laporansppdata', LaporanSPPController::class);

        // laporan kas
        Route::get('laporankasdata/cetak_kas_pdf',
            [LaporanKasController::class, 'cetak_kas_pdf']
        )->name('laporankasdata.cetak_kas_pdf');
        Route::get('laporankasdata/show', [LaporanKasController::class, 'show'])->name('laporankasdata.show');

        Route::resource('laporankasdata', LaporanKasController::class);
    });
});
