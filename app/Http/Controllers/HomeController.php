<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\DetailStokMasuk;
use App\Models\DetailTransaksi; // <-- Pastikan ini diimpor
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session; // <-- Tetap diimpor, meskipun flag sesi dihapus

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // --- BAGIAN 1: TOTAL KUMULATIF (SEPANJANG WAKTU) UNTUK TAMPILAN UTAMA ---
        $totalTransaksiAllTime = Transaksi::count();
        $totalStokMasukAllTime = StokMasuk::count();
        $totalProfitAllTime = Transaksi::sum('profit');
        $totalSalesAllTime = Produk::sum(DB::raw('harga * stok'));

        // --- BAGIAN 2: DATA BULANAN UNTUK PERHITUNGAN PERSENTASE PERUBAHAN ---
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $transaksiCurrentMonthCount = Transaksi::whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])->count();
        $transaksiPreviousMonthCount = Transaksi::whereBetween('tanggal_transaksi', [$startOfLastMonth, $endOfLastMonth])->count();
        $transaksiChange = $this->calculatePercentageChange($transaksiCurrentMonthCount, $transaksiPreviousMonthCount);

        $stokMasukCurrentMonthCount = StokMasuk::whereBetween('tanggal_masuk', [$startOfMonth, $endOfMonth])->count();
        $stokMasukPreviousMonthCount = StokMasuk::whereBetween('tanggal_masuk', [$startOfLastMonth, $endOfLastMonth])->count();
        $stokMasukChange = $this->calculatePercentageChange($stokMasukCurrentMonthCount, $stokMasukPreviousMonthCount);

        $profitCurrentMonthSum = Transaksi::whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])->sum('profit');
        $profitPreviousMonthSum = Transaksi::whereBetween('tanggal_transaksi', [$startOfLastMonth, $endOfLastMonth])->sum('profit');
        $profitChange = $this->calculatePercentageChange($profitCurrentMonthSum, $profitPreviousMonthSum);

        $incomingStockValueCurrentMonth = DetailStokMasuk::whereHas('stokMasuk', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('tanggal_masuk', [$startOfMonth, $endOfMonth]);
        })->join('produk', 'detail_stok_masuk.produk_id', '=', 'produk.id')
          ->sum(DB::raw('detail_stok_masuk.jumlah * produk.harga'));

        $incomingStockValuePreviousMonth = DetailStokMasuk::whereHas('stokMasuk', function ($query) use ($startOfLastMonth, $endOfLastMonth) {
            $query->whereBetween('tanggal_masuk', [$startOfLastMonth, $endOfLastMonth]);
        })->join('produk', 'detail_stok_masuk.produk_id', '=', 'produk.id')
          ->sum(DB::raw('detail_stok_masuk.jumlah * produk.harga'));

        $salesChange = $this->calculatePercentageChange($incomingStockValueCurrentMonth, $incomingStockValuePreviousMonth);

        // --- BAGIAN NOTIFIKASI PRODUK KADALUARSA (Muncul Setiap Login) ---
        $expiringProductsForNotification = collect();
        $showExpiringNotification = false;

        $today = now()->startOfDay();
        $threeMonthsFromNow = now()->addMonths(3)->endOfDay();

        // 1. Ambil semua detail stok masuk yang akan kadaluarsa
        $allExpiringDetails = DetailStokMasuk::with(['stokMasuk', 'produk'])
            ->whereBetween('tanggal_kadaluarsa', [$today, $threeMonthsFromNow])
            ->orderBy('tanggal_kadaluarsa', 'asc') // Urutkan berdasarkan tanggal kadaluarsa (FEFO)
            ->get();

        // 2. Ambil total jumlah terjual untuk produk-produk ini
        $productIdsInExpiring = $allExpiringDetails->pluck('produk_id')->unique();
        $totalSoldQuantities = DetailTransaksi::whereIn('produk_id', $productIdsInExpiring)
            ->groupBy('produk_id')
            ->select('produk_id', DB::raw('SUM(jumlah) as total_sold'))
            ->pluck('total_sold', 'produk_id'); // Hasil: [produk_id => total_terjual]

        // 3. Filter produk yang akan kadaluarsa berdasarkan yang sudah terjual (FEFO)
        $remainingSoldQuantities = $totalSoldQuantities->toArray(); // Buat salinan yang bisa dimodifikasi

        foreach ($allExpiringDetails as $detail) {
            $productId = $detail->produk_id;
            $expiringQuantity = $detail->jumlah;
            $soldForThisProduct = $remainingSoldQuantities[$productId] ?? 0;

            if ($soldForThisProduct >= $expiringQuantity) {
                // Jika jumlah terjual lebih besar atau sama dengan jumlah batch kadaluarsa ini,
                // maka batch ini dianggap sudah terjual seluruhnya.
                $remainingSoldQuantities[$productId] -= $expiringQuantity;
            } else {
                // Jika jumlah terjual lebih kecil dari jumlah batch kadaluarsa ini,
                // berarti sebagian atau seluruh batch ini masih ada.
                $remainingQuantityInBatch = $expiringQuantity - $soldForThisProduct;
                if ($remainingQuantityInBatch > 0) {
                    // Tambahkan detail ini ke daftar notifikasi, dengan jumlah yang tersisa
                    $clonedDetail = clone $detail; // Kloning objek agar tidak memodifikasi objek asli
                    $clonedDetail->jumlah = $remainingQuantityInBatch;
                    $expiringProductsForNotification->push($clonedDetail);
                }
                $remainingSoldQuantities[$productId] = 0; // Sisa terjual sudah habis untuk batch ini
            }
        }

        // Set flag untuk menampilkan notifikasi jika ada produk yang tersisa setelah penyaringan
        if ($expiringProductsForNotification->isNotEmpty()) {
            $showExpiringNotification = true;
            // Tidak ada Session::put() di sini, agar muncul setiap kali login
        }


        // Kirim semua data ke view
        return view('home', compact(
            'totalTransaksiAllTime', 'transaksiChange',
            'totalStokMasukAllTime', 'stokMasukChange',
            'totalProfitAllTime', 'profitChange',
            'totalSalesAllTime', 'salesChange',
            'expiringProductsForNotification', // Data produk kadaluarsa untuk notifikasi
            'showExpiringNotification', // Flag untuk menampilkan notifikasi
            'profitCurrentMonthSum' // <-- TAMBAHKAN INI JIKA BELUM ADA

        ));
    }

    /**
     * Helper function to calculate percentage change and determine icon/color.
     *
     * @param float $currentValue
     * @param float $previousValue
     * @return array
     */
    private function calculatePercentageChange($currentValue, $previousValue)
    {
        if ($previousValue == 0) {
            return [
                'percentage' => ($currentValue > 0) ? 'Inf' : '0.0',
                'icon' => ($currentValue > 0) ? 'ti-trending-up' : 'ti-minus',
                'color' => ($currentValue > 0) ? 'success' : 'secondary'
            ];
        }

        $change = (($currentValue - $previousValue) / $previousValue) * 100;
        $icon = 'ti-minus';
        $color = 'secondary';

        if ($change > 0) {
            $icon = 'ti-trending-up';
            $color = 'success';
        } elseif ($change < 0) {
            $icon = 'ti-trending-down';
            $color = 'danger';
        }

        return [
            'percentage' => number_format(abs($change), 1),
            'icon' => $icon,
            'color' => $color
        ];
    }
    
    public function getChartData()
    {
        $tahunIni = Carbon::now()->year;
        $bulan = range(1, 12);

        // 1. Data untuk Grafik Penjualan (Stok Masuk vs Transaksi)
        $stokMasuk = DB::table('stok_masuk')
            ->select(
                DB::raw('MONTH(tanggal_masuk) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereYear('tanggal_masuk', $tahunIni)
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->all();

        $transaksi = DB::table('transaksi')
            ->select(
                DB::raw('MONTH(tanggal_transaksi) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereYear('tanggal_transaksi', $tahunIni)
            ->groupBy('bulan')
            ->pluck('jumlah', 'bulan')
            ->all();

        // 2. Data untuk Grafik Pendapatan (Profit Bulanan)
        $pendapatan = DB::table('transaksi')
            ->select(
                DB::raw('MONTH(tanggal_transaksi) as bulan'),
                DB::raw('SUM(profit) as total')
            )
            ->whereYear('tanggal_transaksi', $tahunIni)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->all();

        // Format data agar sesuai dengan 12 bulan (isi 0 jika tidak ada data)
        $dataStokMasuk = [];
        $dataTransaksi = [];
        $dataPendapatan = [];
        $labelsBulan = [];

        foreach ($bulan as $b) {
            $dataStokMasuk[] = $stokMasuk[$b] ?? 0;
            $dataTransaksi[] = $transaksi[$b] ?? 0;
            $dataPendapatan[] = $pendapatan[$b] ?? 0;
            $labelsBulan[] = Carbon::create()->month($b)->format('M'); // 'Jan', 'Feb', ...
        }

        // Kembalikan data dalam format JSON
        return response()->json([
            'labels' => $labelsBulan,
            'penjualan' => [
                'stok_masuk' => $dataStokMasuk,
                'transaksi' => $dataTransaksi,
            ],
            'pendapatan' => [
                'total' => $dataPendapatan,
            ],
        ]);
    }
}

