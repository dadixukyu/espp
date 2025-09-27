<div class="sidebar-wrapper" data-simplebar="true">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div>
            <img src="assets/images/logo_smk.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">e-SPP+</h4>
        </div>
        <div class="toggle-icon ms-auto">
            <i class='bx bx-arrow-to-left'></i>
        </div>
    </div>

    <!-- Navigation -->
    <ul class="metismenu" id="menu">

        <!-- Beranda -->
        <li>
            <a href="{{ url('/') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Beranda</div>
            </a>
        </li>

        <!-- User Management (khusus Admin) -->
        @if (getLevel() == '1')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-user-circle'></i></div>
                    <div class="menu-title">User Management</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('userdata.index') }}">
                            <i class="bx bx-id-card"></i> User
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Parameter -->
        @if (getLevel() == '1' || getLevel() == '3')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-slideshow'></i></div>
                    <div class="menu-title">Parameter</div>
                </a>
                <ul>
                    <li><a href="{{ route('partahundata.index') }}"><i class="bx bx-calendar-event"></i> Tahun
                            Ajaran</a></li>
                    <li><a href="{{ route('parkelasdata.index') }}"><i class="bx bx-buildings"></i> Kelas</a></li>
                    <li><a href="{{ route('parsppdata.index') }}"><i class="bx bx-wallet-alt"></i> Nominal SPP</a></li>
                    <li><a href="{{ route('parbiayadata.index') }}"><i class="bx bx-purchase-tag-alt"></i> Biaya</a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Menu Utama -->
        @if (getLevel() == '1' || getLevel() == '3')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-folder'></i></div>
                    <div class="menu-title">Menu Utama</div>
                </a>
                <ul>
                    <li><a href="{{ route('pendaftarandata.index') }}"><i class="bx bx-user-plus"></i> Pendaftaran Siswa
                            Baru</a></li>
                    <li><a href="{{ route('tagihanlaindata.index') }}"><i class="bx bx-file"></i> Tagihan Lain</a></li>
                    <li><a href="{{ route('siswadata.index') }}"><i class="bx bx-group"></i> Data Siswa</a></li>
                    <li><a href="{{ route('tagihan_sppdata.index') }}"><i class="bx bx-receipt"></i> Tagihan SPP</a>
                    </li>
                </ul>
            </li>
        @endif

        <!-- Menu Lain -->
        @if (getLevel() == '1' || getLevel() == '3')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-wallet'></i></div>
                    <div class="menu-title">Menu Lain</div>
                </a>
                <ul>
                    <li><a href="{{ route('kasdata.index') }}"><i class="bx bx-coin-stack"></i> Kas</a></li>
                    <li><a href="{{ route('pindahkelasdata.index') }}"><i class="bx-transfer"></i> Pindah
                            Kelas</a></li>
                    <li><a href="{{ route('penampungdata.index') }}"><i class="bx bx-group"></i> Penampung Siswa
                        </a></li>
                </ul>

            </li>
        @endif

        <!-- Laporan -->
        @if (getLevel() == '1' || getLevel() == '2' || getLevel() == '3')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-pie-chart-alt-2'></i></div>
                    <div class="menu-title">Laporan</div>
                </a>
                <ul>
                    <li><a href="{{ route('laporanpendaftarandata.index') }}"><i class="bx bx-spreadsheet"></i>
                            Pendaftaran</a></li>
                    <li><a href="{{ route('laporansppdata.index') }}"><i class="bx bx-bar-chart-square"></i> SPP</a>
                    </li>
                    <li><a href="{{ route('laporankasdata.index') }}"><i class="bx bx-file-find"></i> KAS</a></li>
                </ul>
            </li>
        @endif

    </ul>
    <!-- End Navigation -->
</div>
