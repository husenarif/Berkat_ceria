<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DetailStokMasukController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Models\kategori;
use App\Models\produk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SatuanProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserController;
use App\Models\Transaksi;

use function PHPUnit\Framework\returnSelf;
use App\Http\Controllers\PublicController; // <-- Tambahkan ini nanti



Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

    Route::get('/stok-produk', [PublicController::class, 'index'])->name('public.stok.index');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::fallback(function () {
    return view('404');
});

Route::resource('produk', ProdukController::class);

Route::resource('kategori', KategoriController::class);

Route::resource('supplier', SupplierController::class);

Route::get('stok_masuk/expiring-soon', [StokMasukController::class, 'expiringSoon'])->name('stok_masuk.expiring_soon');
Route::resource('stok_masuk', StokMasukController::class);

Route::delete('/detail-stok-masuk/{id}', [DetailStokMasukController::class, 'destroy'])->name('detail_stok_masuk.destroy');

Route::resource('transaksi', TransaksiController::class);

Route::resource('detail_transaksi', DetailTransaksiController::class);

Route::resource('satuan_produk', SatuanProdukController::class);

Route::resource('user', UserController::class);

Route::resource('history', HistoryController::class);


Route::middleware(['auth'])->group(function () {
    Route::get('/password/edit', [PasswordController::class, 'edit'])->name('password.edit.local');
    Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update.new');

    // Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    // Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    // Route::post('password/update', [ResetPasswordController::class, 'reset'])->name('password.update');


    Route::post('user-update-role', [UserController::class, 'updateRole'])->name('user.update-role');

    Route::get('/laporan', [App\Http\Controllers\ReportController::class, 'index'])->name('laporan.index');


    // Rute untuk mengambil data grafik dashboard
    Route::get('/dashboard/chart-data', [HomeController::class, 'getChartData'])->name('dashboard.chart.data')->middleware('auth');


});
