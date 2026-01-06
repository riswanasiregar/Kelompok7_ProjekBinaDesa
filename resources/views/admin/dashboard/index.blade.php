@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Hero Header with Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 16px;">
                <div class="card-body position-relative" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ri-dashboard-3-line ri-36px me-3"></i>
                                <div>
                                    <h3 class="text-white mb-1 fw-bold">Dashboard Overview</h3>
                                    <p class="text-white-75 mb-0">Monitor dan kelola program bantuan sosial secara real-time</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <form method="GET" action="{{ route('dashboard') }}">
                                <div class="input-group shadow-sm" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 12px; border: 1px solid rgba(255,255,255,0.2);">
                                    <label class="input-group-text bg-transparent border-0 text-white" for="filterTahun">
                                        <i class="ri-calendar-line ri-20px"></i>
                                    </label>
                                    <select class="form-select bg-transparent border-0 text-white fw-semibold" id="filterTahun" name="tahun" onchange="this.form.submit()" style="color: white !important;">
                                        <option value="all" {{ $tahun == 'all' ? 'selected' : '' }} style="color: #333;">Semua Tahun</option>
                                        @foreach($listTahun as $t)
                                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }} style="color: #333;">{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="position-absolute" style="bottom: -20px; right: -20px; opacity: 0.1;">
                        <i class="ri-pie-chart-2-line" style="font-size: 200px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards with Hover Effects -->
    <div class="row g-4 mb-4">
        <!-- Total Program -->
        <div class="col-xl-3 col-sm-6">
            <div class="card stat-card border-0 shadow-sm h-100" style="border-radius: 16px; transition: all 0.3s ease; cursor: pointer;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar flex-shrink-0" style="width: 56px; height: 56px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-hand-heart-line ri-28px text-white"></i>
                        </div>
                        <div class="text-end">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Program</small>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold" style="font-size: 2rem;">{{ number_format($totalProgram) }}</h3>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-label-primary" style="font-size: 0.7rem; padding: 4px 8px;">
                            <i class="ri-arrow-up-line"></i> Total Program
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Warga -->
        <div class="col-xl-3 col-sm-6">
            <div class="card stat-card border-0 shadow-sm h-100" style="border-radius: 16px; transition: all 0.3s ease; cursor: pointer;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar flex-shrink-0" style="width: 56px; height: 56px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-group-line ri-28px text-white"></i>
                        </div>
                        <div class="text-end">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Warga</small>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold" style="font-size: 2rem;">{{ number_format($totalWarga) }}</h3>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-label-success" style="font-size: 0.7rem; padding: 4px 8px;">
                            <i class="ri-user-line"></i> Total Warga
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pendaftar -->
        <div class="col-xl-3 col-sm-6">
            <div class="card stat-card border-0 shadow-sm h-100" style="border-radius: 16px; transition: all 0.3s ease; cursor: pointer;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar flex-shrink-0" style="width: 56px; height: 56px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-survey-line ri-28px text-white"></i>
                        </div>
                        <div class="text-end">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Pendaftar</small>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold" style="font-size: 2rem;">{{ number_format($totalPendaftar) }}</h3>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-label-info" style="font-size: 0.7rem; padding: 4px 8px;">
                            <i class="ri-file-list-line"></i> Total Pendaftar
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penerima -->
        <div class="col-xl-3 col-sm-6">
            <div class="card stat-card border-0 shadow-sm h-100" style="border-radius: 16px; transition: all 0.3s ease; cursor: pointer;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar flex-shrink-0" style="width: 56px; height: 56px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-user-received-line ri-28px text-white"></i>
                        </div>
                        <div class="text-end">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Penerima</small>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold" style="font-size: 2rem;">{{ number_format($totalPenerima) }}</h3>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-label-warning" style="font-size: 0.7rem; padding: 4px 8px;">
                            <i class="ri-shield-check-line"></i> Total Penerima
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Anggaran dengan Progress Bars -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar me-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-money-dollar-circle-line ri-24px text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Ringkasan Keuangan</h5>
                            <small class="text-muted">Status anggaran dan penyaluran dana</small>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Total Anggaran -->
                        <div class="col-md-4">
                            <div class="p-3" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-radius: 12px; border-left: 4px solid #667eea;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <small class="text-muted fw-semibold text-uppercase" style="font-size: 0.7rem;">Total Anggaran</small>
                                    <i class="ri-wallet-3-line ri-20px text-primary"></i>
                                </div>
                                <h4 class="mb-0 fw-bold text-primary">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</h4>
                            </div>
                        </div>

                        <!-- Total Penyaluran -->
                        <div class="col-md-4">
                            <div class="p-3" style="background: linear-gradient(135deg, rgba(17, 153, 142, 0.1) 0%, rgba(56, 239, 125, 0.1) 100%); border-radius: 12px; border-left: 4px solid #38ef7d;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <small class="text-muted fw-semibold text-uppercase" style="font-size: 0.7rem;">Total Penyaluran</small>
                                    <i class="ri-hand-coin-line ri-20px text-success"></i>
                                </div>
                                <h4 class="mb-0 fw-bold text-success">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</h4>
                            </div>
                        </div>

                        <!-- Sisa Anggaran -->
                        <div class="col-md-4">
                            <div class="p-3" style="background: linear-gradient(135deg, rgba(250, 112, 154, 0.1) 0%, rgba(254, 225, 64, 0.1) 100%); border-radius: 12px; border-left: 4px solid #fa709a;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <small class="text-muted fw-semibold text-uppercase" style="font-size: 0.7rem;">Sisa Anggaran</small>
                                    <i class="ri-safe-line ri-20px text-warning"></i>
                                </div>
                                <h4 class="mb-0 fw-bold text-warning">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Penyerapan -->
                    <div class="mt-4 p-3" style="background: linear-gradient(to right, rgba(102, 126, 234, 0.05), rgba(56, 239, 125, 0.05)); border-radius: 12px;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">Tingkat Penyerapan Anggaran</span>
                            <span class="badge bg-success fw-semibold" style="font-size: 0.85rem;">{{ number_format($persentasePenyerapan, 2) }}%</span>
                        </div>
                        <div class="progress" style="height: 12px; border-radius: 8px; background-color: #e9ecef;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ number_format($persentasePenyerapan, 2) }}%; background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%); border-radius: 8px;"
                                 aria-valuenow="{{ $persentasePenyerapan }}"
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted"><i class="ri-checkbox-circle-line"></i> Tersalurkan</small>
                            <small class="text-muted"><i class="ri-error-warning-line"></i> Tersisa</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Cards dengan Animasi -->
    <div class="row g-4 mb-4">
        <!-- Status Pendaftar -->
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-survey-line ri-20px text-white"></i>
                        </div>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Status Pendaftar</h5>
                            <small class="text-muted">Tracking pendaftaran real-time</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="status-box p-3" style="background: rgba(255, 159, 67, 0.1); border-radius: 12px; transition: all 0.3s ease;">
                                <div class="mb-3">
                                    <div class="mx-auto" style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff9f43 0%, #ffc371 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(255, 159, 67, 0.3);">
                                        <i class="ri-time-line ri-28px text-white"></i>
                                    </div>
                                </div>
                                <h3 class="mb-1 fw-bold">{{ number_format($pendaftarPending) }}</h3>
                                <small class="text-muted fw-semibold">Pending</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="status-box p-3" style="background: rgba(40, 199, 111, 0.1); border-radius: 12px; transition: all 0.3s ease;">
                                <div class="mb-3">
                                    <div class="mx-auto" style="width: 60px; height: 60px; background: linear-gradient(135deg, #28c76f 0%, #48d494 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);">
                                        <i class="ri-checkbox-circle-line ri-28px text-white"></i>
                                    </div>
                                </div>
                                <h3 class="mb-1 fw-bold">{{ number_format($pendaftarDiterima) }}</h3>
                                <small class="text-muted fw-semibold">Diterima</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="status-box p-3" style="background: rgba(234, 84, 85, 0.1); border-radius: 12px; transition: all 0.3s ease;">
                                <div class="mb-3">
                                    <div class="mx-auto" style="width: 60px; height: 60px; background: linear-gradient(135deg, #ea5455 0%, #f08182 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(234, 84, 85, 0.3);">
                                        <i class="ri-close-circle-line ri-28px text-white"></i>
                                    </div>
                                </div>
                                <h3 class="mb-1 fw-bold">{{ number_format($pendaftarDitolak) }}</h3>
                                <small class="text-muted fw-semibold">Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Penerima -->
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-user-received-line ri-20px text-white"></i>
                        </div>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Status Penerima</h5>
                            <small class="text-muted">Monitoring penerimaan bantuan</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center g-4">
                        <div class="col-6">
                            <div class="status-box p-4" style="background: rgba(40, 199, 111, 0.1); border-radius: 12px; transition: all 0.3s ease;">
                                <div class="mb-3">
                                    <div class="mx-auto" style="width: 70px; height: 70px; background: linear-gradient(135deg, #28c76f 0%, #48d494 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);">
                                        <i class="ri-shield-check-line ri-32px text-white"></i>
                                    </div>
                                </div>
                                <h3 class="mb-1 fw-bold">{{ number_format($penerimaSudahMenerima) }}</h3>
                                <small class="text-muted fw-semibold">Sudah Menerima</small>
                                <div class="mt-2">
                                    <span class="badge bg-success" style="font-size: 0.7rem;">
                                        <i class="ri-check-double-line"></i> Selesai
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="status-box p-4" style="background: rgba(255, 159, 67, 0.1); border-radius: 12px; transition: all 0.3s ease;">
                                <div class="mb-3">
                                    <div class="mx-auto" style="width: 70px; height: 70px; background: linear-gradient(135deg, #ff9f43 0%, #ffc371 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(255, 159, 67, 0.3);">
                                        <i class="ri-hourglass-line ri-32px text-white"></i>
                                    </div>
                                </div>
                                <h3 class="mb-1 fw-bold">{{ number_format($penerimaBelumMenerima) }}</h3>
                                <small class="text-muted fw-semibold">Belum Menerima</small>
                                <div class="mt-2">
                                    <span class="badge bg-warning" style="font-size: 0.7rem;">
                                        <i class="ri-time-line"></i> Menunggu
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics -->
    <div class="row g-4 mb-4">
        <!-- Grafik Penyaluran -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-bar-chart-box-line ri-20px text-white"></i>
                            </div>
                            <div>
                                <h5 class="card-title m-0 fw-bold">Penyaluran Per Bulan</h5>
                                <small class="text-muted">Tren penyaluran tahun {{ $tahun == 'all' ? 'semua periode' : $tahun }}</small>
                            </div>
                        </div>
                        <span class="badge bg-label-primary fw-semibold">
                            <i class="ri-calendar-line"></i> {{ $tahun == 'all' ? 'All Time' : $tahun }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="chartPenyaluranPerBulan" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Programs -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-trophy-line ri-20px text-white"></i>
                        </div>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Top 5 Program</h5>
                            <small class="text-muted">Program dengan penerima terbanyak</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($topProgram as $index => $program)
                    <div class="d-flex align-items-center mb-3 p-3 position-relative" style="background: linear-gradient(to right, rgba({{ $index == 0 ? '255, 215, 0' : ($index == 1 ? '192, 192, 192' : ($index == 2 ? '205, 127, 50' : '102, 126, 234')) }}, 0.1), transparent); border-radius: 12px; border-left: 3px solid {{ $index == 0 ? '#FFD700' : ($index == 1 ? '#C0C0C0' : ($index == 2 ? '#CD7F32' : '#667eea')) }};">
                        <div class="position-relative me-3">
                            <div class="badge fw-bold" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 10px; background: {{ $index == 0 ? 'linear-gradient(135deg, #FFD700, #FFA500)' : ($index == 1 ? 'linear-gradient(135deg, #C0C0C0, #808080)' : ($index == 2 ? 'linear-gradient(135deg, #CD7F32, #8B4513)' : 'linear-gradient(135deg, #667eea, #764ba2)')) }}; color: white; font-size: 1rem;">
                                {{ $index + 1 }}
                            </div>
                            @if($index < 3)
                            <i class="ri-medal-line position-absolute" style="top: -8px; right: -8px; font-size: 1.2rem; color: {{ $index == 0 ? '#FFD700' : ($index == 1 ? '#C0C0C0' : '#CD7F32') }};"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">{{ $program->nama_program }}</h6>
                            <div class="d-flex align-items-center">
                                <i class="ri-group-line ri-16px me-1 text-primary"></i>
                                <small class="text-muted fw-semibold">{{ number_format($program->penerima_count) }} Penerima</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <div class="mb-3" style="width: 80px; height: 80px; background: rgba(102, 126, 234, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="ri-inbox-line" style="font-size: 2.5rem; color: #667eea;"></i>
                        </div>
                        <p class="fw-semibold">Belum ada data program</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities dengan Timeline -->
    <div class="row g-4 mb-4">
        <!-- Penyaluran Terbaru -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-history-line ri-20px text-white"></i>
                        </div>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Penyaluran Terbaru</h5>
                            <small class="text-muted">Aktivitas penyaluran terkini</small>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($penyaluranTerbaru as $item)
                    <div class="timeline-item position-relative mb-4 pb-3" style="border-left: 2px solid #e9ecef; padding-left: 1.5rem;">
                        <div class="position-absolute" style="left: -9px; top: 0; width: 16px; height: 16px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
                        <div class="p-3" style="background: linear-gradient(135deg, rgba(17, 153, 142, 0.05) 0%, rgba(56, 239, 125, 0.05) 100%); border-radius: 10px; border-left: 3px solid #38ef7d;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="mb-0 fw-semibold">{{ $item->penerima->warga->nama ?? '-' }}</h6>
                                <span class="badge bg-success" style="font-size: 0.65rem;">
                                    <i class="ri-check-line"></i> Selesai
                                </span>
                            </div>
                            <small class="text-muted d-block mb-1">
                                <i class="ri-hand-heart-line me-1"></i>{{ $item->program->nama_program ?? '-' }}
                            </small>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="fw-semibold text-success">{{ $item->nilai_formatted }} - Tahap {{ $item->tahap_ke }}</small>
                                <small class="text-muted">
                                    <i class="ri-calendar-line me-1"></i>{{ $item->tanggal->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <div class="mb-3" style="width: 80px; height: 80px; background: rgba(17, 153, 142, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="ri-inbox-line" style="font-size: 2.5rem; color: #11998e;"></i>
                        </div>
                        <p class="fw-semibold">Belum ada penyaluran</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pendaftar Terbaru -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-survey-line ri-20px text-white"></i>
                        </div>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Pendaftar Terbaru</h5>
                            <small class="text-muted">Registrasi terkini</small>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($pendaftarTerbaru as $item)
                    <div class="timeline-item position-relative mb-4 pb-3" style="border-left: 2px solid #e9ecef; padding-left: 1.5rem;">
                        <div class="position-absolute" style="left: -9px; top: 0; width: 16px; height: 16px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
                        <div class="p-3" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.05) 0%, rgba(0, 242, 254, 0.05) 100%); border-radius: 10px; border-left: 3px solid #4facfe;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="mb-0 fw-semibold">{{ $item->warga->nama ?? '-' }}</h6>
                                <span class="{{ $item->status_label['class'] }}" style="font-size: 0.65rem;">
                                    {{ $item->status_label['label'] }}
                                </span>
                            </div>
                            <small class="text-muted d-block mb-1">
                                <i class="ri-hand-heart-line me-1"></i>{{ $item->program->nama_program ?? '-' }}
                            </small>
                            <small class="text-muted">
                                <i class="ri-time-line me-1"></i>{{ $item->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <div class="mb-3" style="width: 80px; height: 80px; background: rgba(79, 172, 254, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="ri-inbox-line" style="font-size: 2.5rem; color: #4facfe;"></i>
                        </div>
                        <p class="fw-semibold">Belum ada pendaftar</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Verifikasi Terbaru -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="ri-check-double-line ri-20px text-white"></i>
                        </div>
                        <div>
                            <h5 class="card-title m-0 fw-bold">Verifikasi Terbaru</h5>
                            <small class="text-muted">Proses verifikasi</small>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($verifikasiTerbaru as $item)
                    <div class="timeline-item position-relative mb-4 pb-3" style="border-left: 2px solid #e9ecef; padding-left: 1.5rem;">
                        <div class="position-absolute" style="left: -9px; top: 0; width: 16px; height: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
                        <div class="p-3" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border-radius: 10px; border-left: 3px solid #667eea;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="mb-0 fw-semibold">{{ $item->pendaftar->warga->nama ?? '-' }}</h6>
                                <span class="badge bg-primary" style="font-size: 0.65rem;">
                                    <i class="ri-verified-badge-line"></i> {{ $item->kategori_skor }}
                                </span>
                            </div>
                            <small class="text-muted d-block mb-1">
                                <i class="ri-hand-heart-line me-1"></i>{{ $item->pendaftar->program->nama_program ?? '-' }}
                            </small>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="fw-semibold text-primary">Skor: {{ $item->skor }}</small>
                                <small class="text-muted">
                                    <i class="ri-calendar-line me-1"></i>{{ $item->tanggal->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <div class="mb-3" style="width: 80px; height: 80px; background: rgba(102, 126, 234, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="ri-inbox-line" style="font-size: 2.5rem; color: #667eea;"></i>
                        </div>
                        <p class="fw-semibold">Belum ada verifikasi</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    /* Hover Effects untuk Stat Cards */
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }

    /* Status Box Hover */
    .status-box:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Smooth Scrollbar */
    .card-body::-webkit-scrollbar {
        width: 6px;
    }

    .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .card-body::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .card-body::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    /* Timeline Animation */
    .timeline-item {
        animation: slideInLeft 0.5s ease-out;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Card Entrance Animation */
    .card {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Pulse Animation for Icons */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .avatar:hover {
        animation: pulse 1s infinite;
    }

    /* Gradient Text */
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Select styling for white text */
    #filterTahun option {
        background-color: white;
        color: #333;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart Penyaluran Per Bulan dengan Gradient
    const ctx = document.getElementById('chartPenyaluranPerBulan');
    if (ctx) {
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
        gradient.addColorStop(1, 'rgba(118, 75, 162, 0.2)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Des'],
                datasets: [{
                    label: 'Penyaluran (Rp)',
                    data: @json($chartPenyaluranPerBulan),
                    backgroundColor: gradient,
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(102, 126, 234, 1)',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#333',
                        bodyColor: '#666',
                        borderColor: '#667eea',
                        borderWidth: 2,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000000) {
                                    return 'Rp ' + (value / 1000000000).toFixed(1) + 'M';
                                } else if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                }
                                return 'Rp ' + value.toLocaleString('id-ID');
                            },
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    // Counter Animation
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString('id-ID');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString('id-ID');
            }
        }, 20);
    }

    // Trigger animations on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease-out';
        observer.observe(card);
    });
});
</script>
@endpush
