<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\History;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TransaksiController extends Controller
{
    public function index()
    {
        $title = 'Transaksi Produk!';
        $text = "Transaksi dihapus secara permanen, apakah anda yakin?";
        confirmDelete($title, $text); // Pastikan fungsi ini didefinisikan di tempat yang bisa diakses

        // Eager load relasi 'user' untuk menghindari N+1 problem
        // Gunakan paginate() jika data transaksi bisa sangat banyak
        $transaksi = Transaksi::with(['user', 'detailTransaksi.produk'])->latest()->get();
        // Jika Anda ingin menampilkan 'total_harga', 'bayar', 'kembalian' di index,
        // pastikan kolom-kolom tersebut ada di tabel 'transaksi' dan dimuat di sini.
        // Dari diskusi sebelumnya, ini sudah ada.

        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        // Eager load relasi yang diperlukan jika ada (misal: produk.kategori)
        $produk = Produk::all();
        $users = User::all();
        $kode_transaksi = 'TRX' . now()->format('YmdHis'); // Generate kode transaksi unik

        return view('transaksi.create', compact('produk', 'users', 'kode_transaksi'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksi,kode_transaksi',
            'user_id' => 'required|exists:users,id',
            'tanggal_transaksi' => 'required|date',
            'produk_id' => 'required|array|min:1', // Pastikan ada setidaknya satu produk
            'produk_id.*' => 'required|exists:produk,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric', // Ini dari frontend, akan divalidasi ulang
            'bayar' => 'required|numeric|min:0', // Bayar tidak boleh negatif
            'kembalian' => 'nullable|numeric', // Kembalian bisa null atau negatif jika kurang bayar
            'deskripsi' => 'nullable|string',

        ], [
            'tanggal_transaksi.required' => 'Tanggal Transaksi harus diisi.',
            'produk_id.min' => 'Setidaknya harus ada satu produk dalam transaksi.',
            // Tambahkan pesan validasi lain jika diperlukan
        ]);

        DB::beginTransaction();
        try {
            $calculatedTotalHargaPenjualan = 0;
            $calculatedTotalModal = 0; // Variabel baru untuk total modal

            foreach ($validatedData['produk_id'] as $index => $produk_id) {
                $jumlah = $validatedData['jumlah'][$index];
                $harga_jual_satuan = $validatedData['harga_satuan'][$index];

                // Ambil harga modal produk dari database
                $produk = Produk::find($produk_id);
                if (!$produk) {
                    throw new \Exception("Produk dengan ID {$produk_id} tidak ditemukan.");
                }
                $modal_harga_satuan = $produk->modal_harga; // Ambil modal_harga dari produk

                $calculatedTotalHargaPenjualan += ($jumlah * $harga_jual_satuan);
                $calculatedTotalModal += ($jumlah * $modal_harga_satuan); // Hitung total modal
            }

            // Verifikasi total_harga dari frontend dengan yang dihitung di backend
            if (round($calculatedTotalHargaPenjualan, 2) !== round($validatedData['total_harga'], 2)) {
                throw new \Exception("Total harga tidak sesuai. Terdeteksi manipulasi data.");
            }

            $calculatedKembalian = $validatedData['bayar'] - $calculatedTotalHargaPenjualan;
            $calculatedProfit = $calculatedTotalHargaPenjualan - $calculatedTotalModal; // Hitung profit

            // Buat transaksi utama
            $transaksi = Transaksi::create([
                'kode_transaksi' => $validatedData['kode_transaksi'],
                'user_id' => $validatedData['user_id'],
                'deskripsi' => $validatedData['deskripsi'],
                'tanggal_transaksi' => $validatedData['tanggal_transaksi'],
                'total_harga' => $calculatedTotalHargaPenjualan, // Gunakan total yang dihitung di backend
                'bayar' => $validatedData['bayar'],
                'kembalian' => $calculatedKembalian, // Gunakan kembalian yang dihitung di backend
                'profit' => $calculatedProfit, // <--- SIMPAN PROFIT DI SINI
            ]);

            // Simpan detail transaksi dan update stok
            foreach ($validatedData['produk_id'] as $index => $produk_id) {
                $jumlah = $validatedData['jumlah'][$index];
                $harga_jual_satuan = $validatedData['harga_satuan'][$index];

                $produk = Produk::find($produk_id); // Ambil ulang produk untuk detail
                $modal_harga_satuan = $produk->modal_harga; // Ambil modal_harga dari produk

                $subtotal_penjualan = $jumlah * $harga_jual_satuan;
                $modal_subtotal = $jumlah * $modal_harga_satuan; // Hitung modal subtotal

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk_id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga_jual_satuan,
                    'subtotal' => $subtotal_penjualan,
                    'modal_subtotal' => $modal_subtotal, // <--- SIMPAN MODAL SUBTOTAL DI SINI
                ]);

                // Update stok produk
                if ($produk) {
                    $produk->stok -= $jumlah;
                    $produk->save();
                } else {
                    throw new \Exception("Produk dengan ID {$produk_id} tidak ditemukan saat mengurangi stok.");
                }
            }

            $jeneng = auth()->user()->name;
            $log_history = [
                'aktifitas' => 'Tambah Transaksi',
                'nama' => $jeneng,
                'detail' => $validatedData['kode_transaksi']
            ];
            History::create($log_history);

            DB::commit();
            Alert::success('Berhasil', 'Transaksi berhasil disimpan.');
            return redirect()->route('transaksi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return back()->withInput();
        }
    }
    // ... (lanjutan dari TransaksiController.php)

    public function edit($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk'])->findOrFail($id);
        $users = User::all();
        $produk = Produk::all();

        return view('transaksi.edit', compact('transaksi', 'users', 'produk'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_transaksi' => 'required|date',
            'kode_transaksi' => 'required|string|exists:transaksi,kode_transaksi',

            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'required|numeric|min:0',
            'bayar' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric',
            'kembalian' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',

        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            $calculatedTotalHargaPenjualan = 0;
            $calculatedTotalModal = 0; // Variabel baru untuk total modal

            // 2. Hitung ulang total harga dan total modal dari data yang disubmit di backend
            foreach ($validatedData['produk_id'] as $index => $produk_id) {
                $jumlah = $validatedData['jumlah'][$index];
                $harga_jual_satuan = $validatedData['harga_satuan'][$index];

                $produk = Produk::find($produk_id);
                if (!$produk) {
                    throw new \Exception("Produk dengan ID {$produk_id} tidak ditemukan.");
                }
                $modal_harga_satuan = $produk->modal_harga;

                $calculatedTotalHargaPenjualan += ($jumlah * $harga_jual_satuan);
                $calculatedTotalModal += ($jumlah * $modal_harga_satuan);
            }

            // Verifikasi total_harga dari frontend dengan yang dihitung di backend
            if (round($calculatedTotalHargaPenjualan, 2) !== round($validatedData['total_harga'], 2)) {
                throw new \Exception("Total harga tidak sesuai. Terdeteksi manipulasi data.");
            }

            $calculatedKembalian = $validatedData['bayar'] - $calculatedTotalHargaPenjualan;
            $calculatedProfit = $calculatedTotalHargaPenjualan - $calculatedTotalModal; // Hitung profit

            // 3. Update data transaksi utama
            $transaksi->update([
                'user_id' => $validatedData['user_id'],
                'tanggal_transaksi' => $validatedData['tanggal_transaksi'],
                'deskripsi' => $validatedData['deskripsi'],

                'total_harga' => $calculatedTotalHargaPenjualan,
                'bayar' => $validatedData['bayar'],
                'kembalian' => $calculatedKembalian,
                'profit' => $calculatedProfit, // <--- UPDATE PROFIT DI SINI
            ]);

            // 4. Proses Detail Transaksi: Kembalikan stok lama, hapus semua yang lama, tambahkan yang baru
            // Kembalikan stok produk dari detail transaksi yang lama sebelum dihapus
            foreach ($transaksi->detailTransaksi as $detail) {
                $produk = Produk::find($detail->produk_id);
                if ($produk) {
                    $produk->stok += $detail->jumlah;
                    $produk->save();
                }
            }

            // Hapus semua detail transaksi lama
            $transaksi->detailTransaksi()->delete();

            // Tambahkan detail transaksi baru dan kurangi stok
            foreach ($validatedData['produk_id'] as $index => $produk_id) {
                $jumlah = $validatedData['jumlah'][$index];
                $harga_jual_satuan = $validatedData['harga_satuan'][$index];

                $produk = Produk::find($produk_id); // Ambil ulang produk untuk detail
                $modal_harga_satuan = $produk->modal_harga; // Ambil modal_harga dari produk

                $subtotal_penjualan = $jumlah * $harga_jual_satuan;
                $modal_subtotal = $jumlah * $modal_harga_satuan; // Hitung modal subtotal

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk_id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga_jual_satuan,
                    'subtotal' => $subtotal_penjualan,
                    'modal_subtotal' => $modal_subtotal, // <--- SIMPAN MODAL SUBTOTAL DI SINI
                ]);

                if ($produk) {
                    $produk->stok -= $jumlah;
                    $produk->save();
                } else {
                    throw new \Exception("Produk dengan ID {$produk_id} tidak ditemukan.");
                }
            }

            $jeneng = auth()->user()->name;
            $log_history = [
                'aktifitas' => 'Edit Transaksi',
                'nama' => $jeneng,
                'detail' => $validatedData['kode_transaksi']
            ];
            History::create($log_history);

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
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            // Kembalikan stok produk untuk setiap detail transaksi yang akan dihapus
            foreach ($transaksi->detailTransaksi as $detail) {
                $produk = Produk::find($detail->produk_id);
                if ($produk) {
                    $produk->stok += $detail->jumlah;
                    $produk->save();
                }
            }

            // Hapus semua detail transaksi yang terkait dengan transaksi utama ini
            $transaksi->detailTransaksi()->delete();

            // Hapus transaksi utama
            $transaksi->delete();

            $jeneng = auth()->user()->name;
            $log_history = [
                'aktifitas' => 'Hapus Transaksi',
                'nama' => $jeneng,
                'detail' => $transaksi->kode_transaksi
            ];
            History::create($log_history);

            DB::commit();
            Alert::success('Berhasil', 'Transaksi dan detailnya berhasil dihapus!');
            return redirect()->route('transaksi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage());
            return back();
        }
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['user', 'detailTransaksi.produk'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }
}
