<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\DetailStokMasuk;
use App\Models\DetailTransaksi;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\SatuanProduk;
use App\Models\User;
use App\Models\Supplier;
use App\Models\History; // <-- Sudah Benar
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class StokMasukController extends Controller
{
    public function index()
    {
        $title = 'Data Stok Masuk!';
        $text = "Stok masuk akan dihapus secara permanen, apakah anda yakin?";
        confirmDelete($title, $text);
        $stokMasuk = StokMasuk::with(['user', 'supplier', 'detailStokMasuk.produk'])->latest()->get();
        return view('stok_masuk.index', compact('stokMasuk'));
    }

    public function create()
    {
        $kategori = Kategori::all();
    $produk = Produk::select('id', 'nama_produk', 'modal_harga', 'satuan_id', 'kategori_id')->get();
        $users = User::all();
        $supplier = Supplier::all();
        $satuan = SatuanProduk::all();
        $kode_stok_masuk = 'SM' . now()->format('YmdHis');
        return view('stok_masuk.create', compact('produk', 'users', 'supplier', 'satuan', 'kode_stok_masuk', 'kategori'));
    }
public function store(Request $request)
{
    $validatedData = $request->validate([
        'kode_stok_masuk' => 'required|string|unique:stok_masuk,kode_stok_masuk',
        'tanggal_masuk' => 'required|date',
        'supplier_id' => 'required',
        'user_id' => 'required|exists:users,id',
        'deskripsi' => 'nullable|string',
        'produk_id' => 'required|array|min:1',
        'produk_id.*' => 'required',
        'jumlah' => 'required|array',
        'jumlah.*' => 'required|integer|min:1',
        'satuan' => 'required|array',
        'satuan.*' => 'required',
        'harga_modal_satuan' => 'nullable|array',
        'harga_modal_satuan.*' => 'nullable|numeric|min:0',
        'harga_jual' => 'nullable|array',
        'harga_jual.*' => 'nullable|numeric|min:0',
        'gambar' => 'nullable|array',
        'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'tanggal_kadaluarsa' => 'nullable|array',
        'tanggal_kadaluarsa.*' => 'nullable|date|after_or_equal:' . Carbon::now()->addMonths(3)->toDateString(),
        'new_kategori_id' => 'nullable|array', // Validasi untuk input kategori baru
        'new_kategori_id.*' => 'nullable',
    ]);

    DB::beginTransaction();
    try {
        $supplierId = $request->supplier_id;
        if (!is_numeric($supplierId)) {
            $newSupplier = Supplier::firstOrCreate(['nama_supplier' => $supplierId]);
            $supplierId = $newSupplier->id;
        }

        $stokMasuk = StokMasuk::create([
            'kode_stok_masuk' => $validatedData['kode_stok_masuk'],
            'tanggal_masuk' => $validatedData['tanggal_masuk'],
            'supplier_id' => $supplierId,
            'user_id' => Auth::id(),
            'deskripsi' => $validatedData['deskripsi'],
        ]);

        foreach ($validatedData['produk_id'] as $index => $produkInput) {
            $produkId = $produkInput;
            $satuanId = $request->satuan[$index];
            if (!is_numeric($satuanId)) {
                $newSatuan = SatuanProduk::firstOrCreate(['nama_satuan' => $satuanId]);
                $satuanId = $newSatuan->id;
            }

            $hargaModalUntukDetail = 0;

            if (!is_numeric($produkId)) {
                // --- PRODUK BARU ---
                $kategoriInput = $request->new_kategori_id[$index];
                if (empty($kategoriInput)) {
                    throw new \Exception("Kategori wajib diisi untuk produk baru: " . $produkId);
                }
                $kategoriId = $kategoriInput;
                if (!is_numeric($kategoriInput)) {
                    $newKategori = Kategori::firstOrCreate(['nama_kategori' => $kategoriInput]);
                    $kategoriId = $newKategori->id;
                }

                $kodeProdukOtomatis = Produk::generateKodeProduk();
                $gambarPath = null;
                if ($request->hasFile("gambar.{$index}")) {
                    $gambarPath = $request->file("gambar.{$index}")->store('produk', 'public');
                }

                $newProduk = Produk::create([
                    'kode_produk' => $kodeProdukOtomatis,
                    'nama_produk' => $produkId,
                    'kategori_id' => $kategoriId,
                    'satuan_id'   => $satuanId,
                    'modal_harga' => $request->harga_modal_satuan[$index],
                    'harga'       => $request->harga_jual[$index],
                    'gambar'      => $gambarPath,
                    'stok' => 0,
                ]);
                
                $produkId = $newProduk->id;
                $hargaModalUntukDetail = $request->harga_modal_satuan[$index];

            } else {
                // --- PRODUK LAMA ---
                $hargaModalUntukDetail = isset($request->harga_modal_satuan[$index]) ? $request->harga_modal_satuan[$index] : 0;
            }

            $jumlah = $validatedData['jumlah'][$index];
            $tanggal_kadaluarsa = $validatedData['tanggal_kadaluarsa'][$index] ?? null;
            $nama_satuan = SatuanProduk::find($satuanId)->nama_satuan;

            DetailStokMasuk::create([
                'stok_masuk_id' => $stokMasuk->id,
                'produk_id' => $produkId,
                'jumlah' => $jumlah,
                'satuan' => $nama_satuan,
                'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
                'harga_modal_satuan' => $hargaModalUntukDetail,
            ]);

            $produkToUpdate = Produk::find($produkId);
            if ($produkToUpdate) {
                $produkToUpdate->stok += $jumlah;
                $produkToUpdate->save();
            }
        }

        $jeneng = auth()->user()->name;
        $log_history = [
            'aktifitas' => 'Tambah Stok Masuk',
            'nama' => $jeneng,
            'detail' => $validatedData['kode_stok_masuk']
        ];
        History::create($log_history);

        DB::commit();
        Alert::success('Berhasil', 'Stok masuk berhasil disimpan.');
        return redirect()->route('stok_masuk.index');
    } catch (\Exception $e) {
        DB::rollBack();
        Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        return back()->withInput();
    }
}


    public function show($id)
    {
        $stokMasuk = StokMasuk::with(['user', 'supplier', 'detailStokMasuk.produk.kategori'])->findOrFail($id);
        return view('stok_masuk.show', compact('stokMasuk'));
    }

    public function edit($id)
    {
        // ==========================================================
        // >> PERBAIKAN: HANYA GUNAKAN SATU QUERY YANG LEBIH BAIK <<
        // ==========================================================
        $stokMasuk = StokMasuk::with([
            'detailStokMasuk' => function ($query) {
                $query->with('produk');
            },
            'user',
            'supplier'
        ])->findOrFail($id);

        $users = User::all();
        $produk = Produk::all();
        $supplier = Supplier::all();
        $kategori = Kategori::all();
        $satuan = SatuanProduk::all();
        return view('stok_masuk.edit', compact('stokMasuk', 'users', 'produk', 'supplier', 'satuan', 'kategori'));
    }
    // Ganti seluruh method update() di StokMasukController.php

    public function update(Request $request, $id)
    {
        $threeMonthsFromNow = Carbon::now()->addMonths(3)->toDateString();

        // ==========================================================
        // >> PERBAIKAN 1: VALIDASI YANG LEBIH FLEKSIBEL <<
        // ==========================================================
        $validatedData = $request->validate([
            'kode_stok_masuk' => 'required|string|unique:stok_masuk,kode_stok_masuk,' . $id,
            'tanggal_masuk' => 'required|date',
            'supplier_id' => 'required', // Cukup 'required', logika akan menangani teks/angka
            'user_id' => 'required|exists:users,id',
            'deskripsi' => 'nullable|string',
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'satuan' => 'required|array',
            'satuan.*' => 'required', // Cukup 'required', logika akan menangani teks/angka
            'harga_modal_satuan' => 'required|array',
            'harga_modal_satuan.*' => 'required|numeric|min:0',
            'harga_jual' => 'required|array',
            'harga_jual.*' => 'required|numeric|min:0',
            'gambar' => 'nullable|array',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tanggal_kadaluarsa' => 'nullable|array',
            'tanggal_kadaluarsa.*' => 'nullable|date|after_or_equal:' . $threeMonthsFromNow,
        ]);

        DB::beginTransaction();
        try {
            $stokMasuk = StokMasuk::findOrFail($id);

            // ==========================================================
            // >> PERBAIKAN 2: LOGIKA SUPPLIER & SATUAN YANG AMAN <<
            // ==========================================================
            $supplierId = $request->supplier_id;
            if (!is_numeric($supplierId)) {
                $newSupplier = Supplier::firstOrCreate(['nama_supplier' => $supplierId]);
                $supplierId = $newSupplier->id;
            }

            $stokMasuk->update([
                'kode_stok_masuk' => $validatedData['kode_stok_masuk'],
                'tanggal_masuk' => $validatedData['tanggal_masuk'],
                'supplier_id' => $supplierId,
                'user_id' => $validatedData['user_id'],
                'deskripsi' => $validatedData['deskripsi'],
            ]);

            foreach ($stokMasuk->detailStokMasuk as $detail) {
                if ($detail->produk) {
                    $detail->produk->decrement('stok', $detail->jumlah);
                }
            }
            $stokMasuk->detailStokMasuk()->delete();

            foreach ($validatedData['produk_id'] as $index => $produk_id) {
                $produk = Produk::find($produk_id);
                if (!$produk) continue;

                $produk->modal_harga = $validatedData['harga_modal_satuan'][$index];
                $produk->harga = $validatedData['harga_jual'][$index];
                if ($request->hasFile("gambar.{$index}")) {
                    if ($produk->gambar) {
                        Storage::disk('public')->delete($produk->gambar);
                    }
                    $produk->gambar = $request->file("gambar.{$index}")->store('produk', 'public');
                }
                $produk->save();

                $satuanInput = $request->satuan[$index];
                $satuanId = $satuanInput;
                if (!is_numeric($satuanInput)) {
                    $newSatuan = SatuanProduk::firstOrCreate(['nama_satuan' => $satuanInput]);
                    $satuanId = $newSatuan->id;
                }
                $nama_satuan = SatuanProduk::find($satuanId)->nama_satuan;

                DetailStokMasuk::create([
                    'stok_masuk_id' => $stokMasuk->id,
                    'produk_id' => $produk_id,
                    'jumlah' => $validatedData['jumlah'][$index],
                    'satuan' => $nama_satuan,
                    'harga_modal_satuan' => $validatedData['harga_modal_satuan'][$index],
                    'tanggal_kadaluarsa' => $validatedData['tanggal_kadaluarsa'][$index] ?? null,
                ]);

                $produk->increment('stok', $validatedData['jumlah'][$index]);
            }

            $jeneng = auth()->user()->name;
            $log_history = [
                'aktifitas' => 'Edit Stok Masuk',
                'nama' => $jeneng,
                'detail' => $stokMasuk->kode_stok_masuk
            ];
            History::create($log_history);

            DB::commit();
            Alert::success('Berhasil', 'Stok masuk berhasil diperbarui!');
            return redirect()->route('stok_masuk.index');
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
            $stokMasuk = StokMasuk::findOrFail($id);
            // ... (Logika destroy Anda tidak berubah)
            foreach ($stokMasuk->detailStokMasuk as $detail) {
                $produk = Produk::find($detail->produk_id);
                if ($produk) {
                    $produk->stok -= $detail->jumlah;
                    $produk->save();
                }
            }
            $stokMasuk->detailStokMasuk()->delete();
            $stokMasuk->delete();

            // Kode History Anda (Sudah Benar)
            $jeneng = auth()->user()->name;
            $log_history = [
                'aktifitas' => 'Hapus Stok Masuk',
                'nama' => $jeneng,
                'detail' => $stokMasuk->kode_stok_masuk
            ];
            History::create($log_history);

            DB::commit();
            Alert::success('Berhasil', 'Stok masuk dan detailnya berhasil dihapus!');
            return redirect()->route('stok_masuk.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus stok masuk: ' . $e->getMessage());
            return back();
        }
    }

    public function expiringSoon()
    {
        // ... (Method ini tidak berubah)
        $today = now()->startOfDay();
        $threeMonthsFromNow = now()->addMonths(3)->endOfDay();
        $allExpiringDetails = DetailStokMasuk::with(['stokMasuk', 'produk'])
            ->whereBetween('tanggal_kadaluarsa', [$today, $threeMonthsFromNow])
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->get();
        $productIdsInExpiring = $allExpiringDetails->pluck('produk_id')->unique();
        $totalSoldQuantities = DetailTransaksi::whereIn('produk_id', $productIdsInExpiring)
            ->groupBy('produk_id')
            ->select('produk_id', DB::raw('SUM(jumlah) as total_sold'))
            ->pluck('total_sold', 'produk_id');
        $expiringProducts = collect();
        $remainingSoldQuantities = $totalSoldQuantities->toArray();
        foreach ($allExpiringDetails as $detail) {
            $productId = $detail->produk_id;
            $expiringQuantity = $detail->jumlah;
            $soldForThisProduct = $remainingSoldQuantities[$productId] ?? 0;
            if ($soldForThisProduct >= $expiringQuantity) {
                $remainingSoldQuantities[$productId] -= $expiringQuantity;
            } else {
                $remainingQuantityInBatch = $expiringQuantity - $soldForThisProduct;
                if ($remainingQuantityInBatch > 0) {
                    $clonedDetail = clone $detail;
                    $clonedDetail->jumlah = $remainingQuantityInBatch;
                    $expiringProducts->push($clonedDetail);
                }
                $remainingSoldQuantities[$productId] = 0;
            }
        }
        return view('stok_masuk.expiring_soon', compact('expiringProducts'));
    }
}
