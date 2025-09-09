<?php

namespace App\Http\Controllers;

use App\Models\DetailStokMasuk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DetailStokMasukController extends Controller
{
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $detailStokMasuk = DetailStokMasuk::findOrFail($id);

            // Dapatkan produk terkait
            $produk = Produk::find($detailStokMasuk->produk_id);

            if ($produk) {
                // Kurangi stok produk karena entri stok masuk ini dihapus
                // (Ini adalah stok masuk, jadi menghapusnya berarti mengurangi stok)
                $produk->stok -= $detailStokMasuk->jumlah;
                $produk->save();
            } else {
                // Opsional: Log error atau berikan pesan jika produk tidak ditemukan
                // Namun, jika foreign key diatur dengan benar, ini seharusnya tidak terjadi.
                // throw new \Exception("Produk dengan ID {$detailStokMasuk->produk_id} tidak ditemukan saat menghapus detail stok masuk.");
            }

            // Hapus record detail stok masuk
            $detailStokMasuk->delete();

            DB::commit();
            Alert::success('Berhasil', 'Detail stok masuk berhasil dihapus dan stok produk dikembalikan.');
            // Redirect kembali ke halaman show stok masuk utama
            return redirect()->route('stok_masuk.show', $detailStokMasuk->stok_masuk_id);
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus detail stok masuk: ' . $e->getMessage());
            return back();
        }
    }
}
