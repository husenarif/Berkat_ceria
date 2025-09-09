@extends('layouts.mantis')

@section('content')
    <div class="container">
        {{-- KARTU INFORMASI PRODUK UTAMA --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                {{-- Judul Halaman --}}
                <h4 class="card-title mb-0">
                    Riwayat Produk: {{ $produk->nama_produk }}
                    <span class="badge bg-light-secondary">{{ $produk->kode_produk }}</span>
                </h4>
                {{-- Tombol Aksi --}}
                <div>
                    <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning btn-sm">Edit Produk</a>
                    <a href="{{ route('produk.index') }}" class="btn btn-danger btn-sm">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        {{-- Tampilkan gambar produk atau placeholder --}}
                        <img src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : 'https://via.placeholder.com/150?text=No+Image' }}"
                            alt="{{ $produk->nama_produk }}" class="img-fluid rounded"
                            style="max-height: 150px; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Kategori:</strong> {{ $produk->kategori->nama_kategori ?? 'N/A' }}
                                </p>
                                <p class="mb-1"><strong>Satuan:</strong> {{ $produk->satuan->nama_satuan ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Stok Saat Ini:</strong> <span
                                        class="fw-bold fs-5">{{ $produk->stok }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Harga Jual:</strong> Rp
                                    {{ number_format($produk->harga, 0, ',', '.') }}</p>
                                <p class="mb-1"><strong>Harga Modal:</strong> Rp
                                    {{ number_format($produk->modal_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- KARTU RIWAYAT STOK MASUK --}}
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-download me-2"></i>Riwayat Stok Masuk</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kode Stok Masuk</th>
                                        <th>Jumlah</th>
                                        <th>Supplier</th>
                                        <th>Tgl. Kadaluarsa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayatStokMasuk as $detail)
                                        <tr>
                                            <td>{{ $detail->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                {{-- Link Navigasi Silang ke Detail Stok Masuk --}}
                                                <a href="{{ route('stok_masuk.show', $detail->stok_masuk_id) }}">
                                                    {{ $detail->stokMasuk->kode_stok_masuk }}
                                                </a>
                                            </td>
                                            <td>+{{ $detail->jumlah }} {{ $detail->satuan }}</td>
                                            <td>{{ $detail->stokMasuk->supplier->nama_supplier ?? 'N/A' }}</td>
                                            <td>{{ $detail->tanggal_kadaluarsa ? \Carbon\Carbon::parse($detail->tanggal_kadaluarsa)->format('d-m-Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada riwayat stok masuk
                                                untuk produk ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU RIWAYAT TRANSAKSI (PENJUALAN) --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-upload me-2"></i>Riwayat Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kode Transaksi</th>
                                        <th>Jumlah Terjual</th>
                                        <th>Harga Saat Transaksi</th>
                                        <th>Profit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayatTransaksi as $detail)
                                        <tr>
                                            <td>{{ $detail->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                {{-- Link Navigasi Silang ke Detail Transaksi --}}
                                                <a href="{{ route('transaksi.show', $detail->transaksi_id) }}">
                                                    {{ $detail->transaksi->kode_transaksi }}
                                                </a>
                                            </td>
                                            <td>-{{ $detail->jumlah }} {{ $detail->produk->satuan->nama_satuan ?? 'N/A' }}
                                            </td>

                                            {{-- PERBAIKAN 1: Gunakan 'harga_satuan' dari detail_transaksi --}}
                                            <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>

                                            {{-- PERBAIKAN 2: Hitung profit per baris (Harga Jual - Harga Modal) --}}
                                            @php
                                                // Ambil harga modal dari produk terkait
                                                $hargaModal = $detail->produk->modal_harga ?? 0;
                                                // Hitung profit untuk baris detail ini
                                                $profitPerItem =
                                                    ($detail->harga_satuan - $hargaModal) * $detail->jumlah;
                                            @endphp
                                            <td>Rp {{ number_format($profitPerItem, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada riwayat penjualan
                                                untuk produk ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
