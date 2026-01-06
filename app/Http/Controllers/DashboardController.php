<?php

namespace App\Http\Controllers;

use App\Models\ProgramBantuan;
use App\Models\Warga;
use App\Models\PendaftarBantuan;
use App\Models\PenerimaBantuan;
use App\Models\RiwayatPenyaluranBantuan;
use App\Models\VerifikasiLapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Jika user BELUM login, redirect ke login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Filter tahun (default tahun sekarang)
        $tahun = $request->input('tahun', now()->year);

        // ============================================
        // STATISTIK UTAMA
        // ============================================

        // Total Program Bantuan
        $totalProgram = ProgramBantuan::when($tahun != 'all', function($q) use ($tahun) {
            $q->where('tahun', $tahun);
        })->count();

        // Total Warga Terdaftar
        $totalWarga = Warga::count();

        // Total Pendaftar
        $totalPendaftar = PendaftarBantuan::when($tahun != 'all', function($q) use ($tahun) {
            $q->whereHas('program', function($q2) use ($tahun) {
                $q2->where('tahun', $tahun);
            });
        })->count();

        // Total Penerima
        $totalPenerima = PenerimaBantuan::when($tahun != 'all', function($q) use ($tahun) {
            $q->whereHas('program', function($q2) use ($tahun) {
                $q2->where('tahun', $tahun);
            });
        })->count();

        // Total Anggaran
        $totalAnggaran = ProgramBantuan::when($tahun != 'all', function($q) use ($tahun) {
            $q->where('tahun', $tahun);
        })->sum('anggaran');

        // Total Penyaluran
        $totalPenyaluran = RiwayatPenyaluranBantuan::when($tahun != 'all', function($q) use ($tahun) {
            $q->whereHas('program', function($q2) use ($tahun) {
                $q2->where('tahun', $tahun);
            });
        })->sum('nilai');

        // Sisa Anggaran
        $sisaAnggaran = $totalAnggaran - $totalPenyaluran;

        // Persentase Penyerapan
        $persentasePenyerapan = $totalAnggaran > 0 ? ($totalPenyaluran / $totalAnggaran) * 100 : 0;

        // ============================================
        // STATISTIK STATUS PENDAFTAR
        // ============================================

        $pendaftarPending = PendaftarBantuan::where('status_seleksi', 'pending')
            ->when($tahun != 'all', function($q) use ($tahun) {
                $q->whereHas('program', function($q2) use ($tahun) {
                    $q2->where('tahun', $tahun);
                });
            })->count();

        $pendaftarDiterima = PendaftarBantuan::where('status_seleksi', 'diterima')
            ->when($tahun != 'all', function($q) use ($tahun) {
                $q->whereHas('program', function($q2) use ($tahun) {
                    $q2->where('tahun', $tahun);
                });
            })->count();

        $pendaftarDitolak = PendaftarBantuan::where('status_seleksi', 'ditolak')
            ->when($tahun != 'all', function($q) use ($tahun) {
                $q->whereHas('program', function($q2) use ($tahun) {
                    $q2->where('tahun', $tahun);
                });
            })->count();

        // ============================================
        // STATISTIK PENERIMA
        // ============================================

        $penerimaSudahMenerima = PenerimaBantuan::whereHas('penyaluran')
            ->when($tahun != 'all', function($q) use ($tahun) {
                $q->whereHas('program', function($q2) use ($tahun) {
                    $q2->where('tahun', $tahun);
                });
            })->count();

        $penerimaBelumMenerima = PenerimaBantuan::whereDoesntHave('penyaluran')
            ->when($tahun != 'all', function($q) use ($tahun) {
                $q->whereHas('program', function($q2) use ($tahun) {
                    $q2->where('tahun', $tahun);
                });
            })->count();

        // ============================================
        // GRAFIK PENYALURAN PER BULAN
        // ============================================

        $penyaluranPerBulan = RiwayatPenyaluranBantuan::selectRaw('MONTH(tanggal) as bulan, SUM(nilai) as total')
            ->when($tahun != 'all', function($q) use ($tahun) {
                $q->whereYear('tanggal', $tahun);
            })
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('total', 'bulan')
            ->toArray();

        // Isi data bulan yang kosong dengan 0
        $chartPenyaluranPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartPenyaluranPerBulan[] = $penyaluranPerBulan[$i] ?? 0;
        }

        // ============================================
        // TOP 5 PROGRAM DENGAN PENERIMA TERBANYAK
        // ============================================

        $topProgram = ProgramBantuan::withCount('penerima')
            ->when($tahun != 'all', function($q) use ($tahun) {
                $q->where('tahun', $tahun);
            })
            ->orderBy('penerima_count', 'desc')
            ->limit(5)
            ->get();

        // ============================================
        // PENYALURAN TERBARU (10 Data)
        // ============================================

        $penyaluranTerbaru = RiwayatPenyaluranBantuan::with(['program', 'penerima.warga'])
            ->when($tahun != 'all', function($q) use ($tahun) {
                $q->whereHas('program', function($q2) use ($tahun) {
                    $q2->where('tahun', $tahun);
                });
            })
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

        // ============================================
        // PENDAFTAR TERBARU (10 Data)
        // ============================================

        $pendaftarTerbaru = PendaftarBantuan::with(['warga', 'program'])
            ->when($tahun != 'all', function($q) use ($tahun) {
                $q->whereHas('program', function($q2) use ($tahun) {
                    $q2->where('tahun', $tahun);
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ============================================
        // VERIFIKASI TERBARU (10 Data)
        // ============================================

        $verifikasiTerbaru = VerifikasiLapangan::with(['pendaftar.warga', 'pendaftar.program'])
            ->when($tahun != 'all', function($q) use ($tahun) {
                $q->whereHas('pendaftar.program', function($q2) use ($tahun) {
                    $q2->where('tahun', $tahun);
                });
            })
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

        // ============================================
        // GRAFIK STATUS VERIFIKASI
        // ============================================

        $verifikasiStats = [
            'Sangat Baik' => VerifikasiLapangan::whereHas('pendaftar.program', function($q) use ($tahun) {
                    if ($tahun != 'all') $q->where('tahun', $tahun);
                })->where('skor', '>=', 85)->count(),
            'Baik' => VerifikasiLapangan::whereHas('pendaftar.program', function($q) use ($tahun) {
                    if ($tahun != 'all') $q->where('tahun', $tahun);
                })->whereBetween('skor', [70, 84])->count(),
            'Cukup' => VerifikasiLapangan::whereHas('pendaftar.program', function($q) use ($tahun) {
                    if ($tahun != 'all') $q->where('tahun', $tahun);
                })->whereBetween('skor', [55, 69])->count(),
            'Kurang' => VerifikasiLapangan::whereHas('pendaftar.program', function($q) use ($tahun) {
                    if ($tahun != 'all') $q->where('tahun', $tahun);
                })->where('skor', '<', 55)->count(),
        ];

        // List tahun untuk filter
        $listTahun = ProgramBantuan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Return view dengan path yang sesuai dengan struktur Anda
        return view('admin.dashboard.index', compact(
            'totalProgram',
            'totalWarga',
            'totalPendaftar',
            'totalPenerima',
            'totalAnggaran',
            'totalPenyaluran',
            'sisaAnggaran',
            'persentasePenyerapan',
            'pendaftarPending',
            'pendaftarDiterima',
            'pendaftarDitolak',
            'penerimaSudahMenerima',
            'penerimaBelumMenerima',
            'chartPenyaluranPerBulan',
            'topProgram',
            'penyaluranTerbaru',
            'pendaftarTerbaru',
            'verifikasiTerbaru',
            'verifikasiStats',
            'tahun',
            'listTahun'
        ));
    }
}
