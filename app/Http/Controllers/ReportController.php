<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\StokMasuk;
use App\Models\DetailStokMasuk;
use App\Models\DetailTransaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $reportType = $request->input('report_type', 'sales_summary');

        $data = [];
        $reportTitle = ''; // <-- Pastikan ini diinisialisasi di sini

        switch ($reportType) {
            case 'sales_summary':
                $reportTitle = 'Laporan Penjualan Detail'; // Ganti judul agar lebih sesuai
                $data = Transaksi::with(['user', 'detailTransaksi.produk']) // Eager load relasi yang dibutuhkan
                    ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
                    ->orderBy('tanggal_transaksi', 'asc')
                    ->get();
                break;

            case 'stock_in_summary':
                $reportTitle = 'Ringkasan Stok Masuk';
                $data = StokMasuk::with(['detailStokMasuk.produk.satuan', 'supplier', 'user'])
                    ->whereBetween('tanggal_masuk', [$startDate, $endDate])
                    ->orderBy('tanggal_masuk', 'asc')
                    ->get();
                break; // <-- BREAK INI YANG HILANG PADA PERBAIKAN SEBELUMNYA

            case 'expiring_products':
                $reportTitle = 'Produk Akan Kadaluarsa';
                $today = Carbon::now()->startOfDay();
                $threeMonthsFromNow = Carbon::now()->addMonths(3)->endOfDay();
                $expiringProducts = DetailStokMasuk::with(['stokMasuk', 'produk'])
                    ->whereBetween('tanggal_kadaluarsa', [$today, $threeMonthsFromNow])
                    // ->whereHas('stokMasuk', function($query) use ($startDate, $endDate) {
                    //     $query->whereBetween('tanggal_masuk', [$startDate, $endDate]);
                    // })
                    ->orderBy('tanggal_kadaluarsa', 'asc')
                    ->get();
                $soldProductQuantities = DetailTransaksi::select('produk_id', DB::raw('SUM(jumlah) as total_sold_qty'))
                    ->groupBy('produk_id')
                    ->pluck('total_sold_qty', 'produk_id');
                $filteredExpiringProducts = collect();
                foreach ($expiringProducts as $detailStokMasuk) {
                    $productId = $detailStokMasuk->produk_id;
                    $currentBatchQty = $detailStokMasuk->jumlah;
                    if (isset($soldProductQuantities[$productId]) && $soldProductQuantities[$productId] > 0) {
                        $soldQtyForProduct = $soldProductQuantities[$productId];
                        if ($soldQtyForProduct >= $currentBatchQty) {
                            $soldProductQuantities[$productId] -= $currentBatchQty;
                        } else {
                            $filteredExpiringProducts->push($detailStokMasuk);
                            $soldProductQuantities[$productId] = 0;
                        }
                    } else {
                        $filteredExpiringProducts->push($detailStokMasuk);
                    }
                }
                $data = $filteredExpiringProducts;
                break; // <-- Pastikan ada break;

            case 'stock_flow':
                $reportTitle = 'Laporan Arus Stok';
                $stockIn = DetailStokMasuk::with(['stokMasuk.user', 'produk'])
                    ->whereHas('stokMasuk', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal_masuk', [$startDate, $endDate]);
                    })
                    ->get()
                    ->map(function ($item) {
                        return (object) [
                            'tanggal' => $item->stokMasuk->tanggal_masuk,
                            'kode' => $item->stokMasuk->kode_stok_masuk,
                            'jenis' => 'Masuk',
                            'nama_produk' => $item->produk->nama_produk ?? 'N/A',
                            'jumlah' => $item->jumlah,
                            'satuan' => $item->satuan,
                            'admin' => $item->stokMasuk->user->name ?? 'N/A',
                            'route' => route('stok_masuk.show', $item->stok_masuk_id)
                        ];
                    });
                $stockOut = DetailTransaksi::with(['transaksi.user', 'produk'])
                    ->whereHas('transaksi', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
                    })
                    ->get()
                    ->map(function ($item) {
                        return (object) [
                            'tanggal' => $item->transaksi->tanggal_transaksi,
                            'kode' => $item->transaksi->kode_transaksi,
                            'jenis' => 'Keluar',
                            'nama_produk' => $item->produk->nama_produk ?? 'N/A',
                            'jumlah' => $item->jumlah,
                            'satuan' => $item->produk->satuan->nama_satuan ?? 'N/A',
                            'admin' => $item->transaksi->user->name ?? 'N/A',
                            'route' => route('transaksi.show', $item->transaksi_id)
                        ];
                    });
                $data = $stockIn->concat($stockOut)->sortBy('tanggal');
                break; // <-- Pastikan ada break;
        }

        return view('laporan.index', compact('data', 'reportTitle', 'startDate', 'endDate', 'reportType'));
    }
}
