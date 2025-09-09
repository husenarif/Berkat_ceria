<?php
// app/Http/Controllers/DetailTransaksiController.php
namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DetailTransaksiController extends Controller
{
    public function index()
    {
        $title = 'Hapus Detail!';
        $text = "Detail Transaksi di hapus secara permanen, apakah anda yakin?";
        confirmDelete($title, $text);
        $detail_transaksi = DetailTransaksi::with(['produk', 'transaksi'])->latest()->get();
        return view('detail_transaksi.index', compact('detail_transaksi'));
    }

    public function create()
    {
        $produk = Produk::all();
        $transaksi = Transaksi::all();
        return view('detail_transaksi.create', compact('produk', 'transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $subtotal = $request->jumlah * $request->harga_satuan;

        // Ambil harga modal produk untuk menghitung modal_subtotal
        $produk = Produk::find($request->produk_id);
        $modal_subtotal = $request->jumlah * $produk->modal_harga;

        DetailTransaksi::create([
            'transaksi_id' => $request->transaksi_id,
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'subtotal' => $subtotal,
            'modal_subtotal' => $modal_subtotal, // <--- SIMPAN MODAL SUBTOTAL DI SINI
        ]);

        return redirect()->route('detail_transaksi.index')->with('success', 'Detail transaksi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $detail_transaksi = DetailTransaksi::findOrFail($id);
        $produk = Produk::all();
        $transaksi = Transaksi::all();
        return view('detail_transaksi.edit', compact('detail_transaksi', 'produk', 'transaksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_transaksi' => 'required|date',
            'produk_id.*' => 'required|exists:produk,id',
            'jumlah.*' => 'required|integer|min:1',
            'harga_satuan.*' => 'required|numeric|min:0',
            'bayar' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            $subtotal_array = [];
            $modal_subtotal_array = []; // Array baru untuk modal subtotal
            foreach ($request->produk_id as $index => $pid) {
                $subtotal_array[] = $request->jumlah[$index] * $request->harga_satuan[$index];

                // Ambil harga modal produk
                $produk = Produk::find($pid);
                $modal_subtotal_array[] = $request->jumlah[$index] * $produk->modal_harga;
            }
            $total_harga_penjualan = array_sum($subtotal_array);
            $total_modal = array_sum($modal_subtotal_array); // Hitung total modal

            $kembalian = $request->bayar - $total_harga_penjualan;
            $profit = $total_harga_penjualan - $total_modal; // Hitung profit

            $transaksi->update([
                'user_id' => $request->user_id,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'total_harga' => $total_harga_penjualan,
                'bayar' => $request->bayar,
                'kembalian' => $kembalian,
                'profit' => $profit, // <--- UPDATE PROFIT DI SINI
            ]);

            // Kembalikan stok sebelum hapus detail
            foreach ($transaksi->detailTransaksi as $detail) {
                $produk = Produk::find($detail->produk_id);
                $produk->stok += $detail->jumlah;
                $produk->save();
            }

            $transaksi->detailTransaksi()->delete();

            foreach ($request->produk_id as $index => $pid) {
                $jumlah = $request->jumlah[$index];
                $harga_satuan = $request->harga_satuan[$index];
                $subtotal = $jumlah * $harga_satuan;

                $produk = Produk::find($pid); // Ambil ulang produk untuk detail
                $modal_subtotal = $jumlah * $produk->modal_harga; // Hitung modal subtotal

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $pid,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga_satuan,
                    'subtotal' => $subtotal,
                    'modal_subtotal' => $modal_subtotal, // <--- SIMPAN MODAL SUBTOTAL DI SINI
                ]);

                $produk->stok -= $jumlah;
                $produk->save();
            }

            DB::commit();
            Alert::success('Berhasil', 'Transaksi berhasil diperbarui!');
            return redirect()->route('transaksi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction(); // Mulai transaksi database
        try {
            $detailTransaksi = DetailTransaksi::findOrFail($id);

            // Dapatkan transaksi utama yang terkait sebelum detail dihapus
            $transaksi = $detailTransaksi->transaksi;

            // 1. Kembalikan stok produk
            $produk = Produk::find($detailTransaksi->produk_id);
            if ($produk) {
                $produk->stok += $detailTransaksi->jumlah;
                $produk->save();
            }

            // 2. Hapus detail transaksi yang dipilih
            $detailTransaksi->delete();

            // 3. Perbarui total_harga, kembalian, dan PROFIT di transaksi utama
            // Hitung ulang total harga penjualan dari semua detail transaksi yang tersisa
            $newTotalHargaPenjualan = $transaksi->detailTransaksi()->sum('subtotal');
            // Hitung ulang total modal dari semua detail transaksi yang tersisa
            $newTotalModal = $transaksi->detailTransaksi()->sum('modal_subtotal'); // <--- BARIS BARU

            $transaksi->total_harga = $newTotalHargaPenjualan;
            $transaksi->kembalian = $transaksi->bayar - $newTotalHargaPenjualan;
            $transaksi->profit = $newTotalHargaPenjualan - $newTotalModal; // <--- UPDATE PROFIT DI SINI
            $transaksi->save();

            DB::commit(); // Commit transaksi jika semua berhasil
            Alert::success('Berhasil', 'Detail transaksi berhasil dihapus!');

            // Redirect kembali ke halaman sebelumnya (misalnya halaman detail transaksi utama)
            return back();
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus detail transaksi: ' . $e->getMessage());
            return back();
        }
    }
}
