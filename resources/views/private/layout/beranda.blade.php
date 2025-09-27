@extends('main')
@section('isi')
    <div class="page-content p-3">

        {{-- Welcome Card --}}
        <div class="card mb-4 shadow-sm rounded-4 border-start border-4 border-primary">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap bg-white py-4 px-4">
                <div>
                    <h2 class="text-primary mb-1" style="font-family: Franklin Gothic Heavy;">
                        <i class='bx bx-smile'></i> Selamat Datang
                    </h2>
                    <p class="text-muted mb-2" style="font-style: italic;">
                        Aplikasi ini memonitor data siswa dan aktivitas sekolah.
                    </p>
                    <p class="mb-0 fs-5">
                        Total Siswa Aktif: <span class="badge bg-success count-up" data-target="{{ $totalSiswa }}">0</span>
                    </p>
                </div>
                <img src="{{ asset('assets/images/dash/main.jpg') }}" alt="Selamat Datang" style="height: 100px;">
            </div>
        </div>

        {{-- Dashboard Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm rounded-4 hover-card text-white gradient-primary">
                    <div class="card-body d-flex align-items-center">
                        <i class='bx bx-group bx-lg me-3'></i>
                        <div>
                            <h6 class="card-title mb-1">Total Siswa</h6>
                            <p class="card-text fs-5">{{ $totalSiswa }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm rounded-4 hover-card text-white gradient-success">
                    <div class="card-body d-flex align-items-center">
                        <i class='bx bx-money bx-lg me-3'></i>
                        <div>
                            <h6 class="card-title mb-1">Total Tagihan</h6>
                            <p class="card-text fs-5">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm rounded-4 hover-card text-white gradient-warning">
                    <div class="card-body d-flex align-items-center">
                        <i class='bx bx-wallet bx-lg me-3'></i>
                        <div>
                            <h6 class="card-title mb-1">Total Bayar</h6>
                            <p class="card-text fs-5">Rp {{ number_format($totalBayar, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Charts --}}
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm rounded-4 fade-in">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><i class='bx bx-group'></i> Siswa Aktif per Kelas</h5>
                        <canvas id="siswaChart" height="130"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm rounded-4 fade-in">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><i class='bx bx-bar-chart'></i> SPP Bulanan</h5>
                        <canvas id="sppChart" height="130"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
        // Count-up animation
        document.querySelectorAll('.count-up').forEach(el => {
            let target = +el.dataset.target;
            let count = 0;
            let step = Math.ceil(target / 100);
            let interval = setInterval(() => {
                count += step;
                if (count >= target) {
                    count = target;
                    clearInterval(interval);
                }
                el.textContent = count;
            }, 15);
        });

        // Fade-in scroll
        const faders = document.querySelectorAll('.fade-in');
        const appearOnScroll = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('appear');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });
        faders.forEach(fader => appearOnScroll.observe(fader));

        // Charts
        const ctxSiswa = document.getElementById('siswaChart').getContext('2d');
        new Chart(ctxSiswa, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: @json($data),
                    backgroundColor: @json($backgroundColor),
                    borderColor: @json($borderColor),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Siswa Aktif per Kelas'
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        const ctxSPP = document.getElementById('sppChart').getContext('2d');
        new Chart(ctxSPP, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                        label: 'Tagihan',
                        data: @json($tagihanBulan),
                        backgroundColor: 'rgba(255,99,132,0.2)',
                        borderColor: 'rgba(255,99,132,1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Bayar',
                        data: @json($bayarBulan),
                        backgroundColor: 'rgba(54,162,235,0.2)',
                        borderColor: 'rgba(54,162,235,1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'SPP Bulanan'
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    {{-- Styles --}}
    <style>
        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .gradient-primary {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
        }

        .gradient-success {
            background: linear-gradient(135deg, #198754, #20c997);
        }

        .gradient-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .fade-in.appear {
            opacity: 1;
            transform: translateY(0);
        }

        .count-up {
            transition: all 0.3s;
        }

        .count-up:hover {
            transform: scale(1.2);
        }
    </style>
@endsection
