<?php

use Illuminate\Support\Facades\Auth;

function cek_date_ddmmyyyy_his_v1($date)
{
    $timestamp = $date;
    $splitTimeStamp = explode(' ', $timestamp);
    $date = $splitTimeStamp[0];
    $time = $splitTimeStamp[1];

    $str = explode('-', $date);
    $bulan = [
        '00' => '00',
        '01' => '01',
        '02' => '02',
        '03' => '03',
        '04' => '04',
        '05' => '05',
        '06' => '06',
        '07' => '07',
        '08' => '08',
        '09' => '09',
        '10' => '10',
        '11' => '11',
        '12' => '12',
    ];

    return $str['2'].'-'.$bulan[$str[1]].'-'.$str[0].' ('.$time.')';
}

function cek_month_v1($month)
{
    $bulan = [
        '0' => '00',
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Maret',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Juni',
        '7' => 'Juli',
        '8' => 'Agustus',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];

    return $bulan[$month];
}

function cek_ddmmyy_v1($date)
{
    $str = explode('-', $date);
    $bulan = [
        '00' => '00',
        '01' => '01',
        '02' => '02',
        '03' => '03',
        '04' => '04',
        '05' => '05',
        '06' => '06',
        '07' => '07',
        '08' => '08',
        '09' => '09',
        '10' => '10',
        '11' => '11',
        '12' => '12',
    ];

    return $str['2'].'-'.$bulan[$str[1]].'-'.$str[0];
}

function cek_ddmmyy_v2($date)
{
    $str = explode('-', $date);
    $bulan = [
        '00' => '00',
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];

    return $str['2'].' '.$bulan[$str[1]].' '.$str[0];
}

function cek_ddmmyy_v3($tanggal)
{
    return date('d-M-Y', strtotime($tanggal));
}

function format_rupiah($angka)
{
    $hasil = number_format($angka, 0, ',', '.');

    return $hasil;
}

function status_actived($angka)
{
    if ($angka == 1) {
        $isi = 'Aktif';
    } elseif ($angka == 2) {
        $isi = 'Tidak Aktif';
    }

    return $isi;
}

function cek_pemberian_kredit($angka)
{
    if ($angka == 1) {
        $isi = 'DEBITUR';
    } elseif ($angka == 2) {
        $isi = 'NILAI AKAD';
    } elseif ($angka == 3) {
        $isi = 'OUTSTANDING';
    }

    return $isi;
}

function cek_status_jenis($angka)
{
    if ($angka == 'nm_bentuk_koperasi') {
        $isi = 'BENTUK KOPERASI';
    } elseif ($angka == 'nm_jenis_koperasi') {
        $isi = 'JENIS KOPERASI';
    } elseif ($angka == 'nm_kelompok_koperasi') {
        $isi = 'KELOMPOK KOPERASI';
    } elseif ($angka == 'nm_sektor_usaha') {
        $isi = 'SEKTOR USAHA';
    }

    return $isi;
}

function status_actived_badge($angka)
{
    if ($angka == 1) {
        $isi = '<span class="badge badge-pill badge-primary">Aktif</span>';
    } elseif ($angka == 0) {
        $isi = '<span class="badge badge-pill badge-danger">Tidak Aktif</span>';
    }

    return $isi;
}

function cekStatusPost($angka)
{
    if ($angka == 1) {
        $isi = 'Publish';
    } elseif ($angka == 2) {
        $isi = 'Draft';
    }

    return $isi;
}

function cekJenisLaman($angka)
{
    if ($angka == 1) {
        $isi = '';
    } elseif ($angka == 2) {
        $isi = '( UPLOAD FILE PDF, WORD, EXCEL )';
    } elseif ($angka == 3) {
        $isi = '( UPLOAD JPG, JPEG, PNG )';
    } elseif ($angka == 4) {
        $isi = '( DATA FORM )';
    } else {
        $isi = '';
    }

    return $isi;
}

function ceknNlaiPelayanan($angka)
{
    if ($angka == 1) {
        $isi = 'Buruk';
    } elseif ($angka == 2) {
        $isi = 'Cukup';
    } elseif ($angka == 3) {
        $isi = 'Baik';
    } elseif ($angka == 4) {
        $isi = 'Sangat Baik';
    }

    return $isi;
}

function terbilangTanggal($tanggal)
{
    // Array untuk menyimpan nama hari, nama bulan, dan bilangan
    $namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Pisahkan tahun, bulan, dan tanggal
    [$tahun, $bulan, $tanggal] = explode('-', $tanggal);

    // Konversi tahun, bulan, dan tanggal menjadi kata
    $tahunKata = terbilang(intval($tahun));
    $bulanKata = $namaBulan[intval($bulan)];
    $tanggalKata = terbilang(intval($tanggal));

    // Dapatkan nama hari
    $namaHariTanggal = date('w', strtotime($tahun.'-'.$bulan.'-'.$tanggal));
    $namaHariKata = $namaHari[$namaHariTanggal];

    // Formatkan hasil
    $hasil = "$namaHariKata tanggal $tanggalKata bulan $bulanKata tahun $tahunKata";

    return $hasil;
}

function terbilang($bilangan)
{
    $angka = [
        'Nol',
        'Satu',
        'Dua',
        'Tiga',
        'Empat',
        'Lima',
        'Enam',
        'Tujuh',
        'Delapan',
        'Sembilan',
        'Sepuluh',
        'Sebelas',
    ];

    if ($bilangan < 12) {
        return $angka[$bilangan];
    } elseif ($bilangan < 20) {
        return terbilang($bilangan - 10).' Belas';
    } elseif ($bilangan < 100) {
        return terbilang($bilangan / 10).' Puluh '.terbilang($bilangan % 10);
    } elseif ($bilangan < 200) {
        return 'Seratus '.terbilang($bilangan - 100);
    } elseif ($bilangan < 1000) {
        return terbilang($bilangan / 100).' Ratus '.terbilang($bilangan % 100);
    } elseif ($bilangan < 2000) {
        return 'Seribu '.terbilang($bilangan - 1000);
    } elseif ($bilangan < 1000000) {
        return terbilang($bilangan / 1000).' Ribu '.terbilang($bilangan % 1000);
    } else {
        return '';
    }
}

// 07-12-2023 rio
function cek_userdata($angka)
{
    if ($angka == 1) {
        $isi = 'Admin';
    } elseif ($angka == 2) {
        $isi = 'Kepala Sekolah';
    } elseif ($angka == 3) {
        $isi = 'Operator';
    } else {
        $isi = 'Tidak Diketahui';
    }

    return $isi;
}

if (! function_exists('getLevel')) {
    function getLevel()
    {
        return Auth::user()->level;
    }
}

// function cek_tglMasaTenggang($masa)
// {
//     $currentDate = date('Y-m-d');
//     $nextDate = date('Y-m-d', strtotime($masa . 'day', strtotime($currentDate)));
//     return $nextDate;
// }
