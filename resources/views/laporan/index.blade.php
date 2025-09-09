@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center no-print">
                <h4 class="card-title">Halaman Laporan</h4>
                <button class="btn btn-secondary" onclick="window.print()">Cetak Laporan</button>
            </div>
            <div class="card-body">
                {{-- Form Filter (tidak akan tercetak) --}}
                <form action="{{ route('laporan.index') }}" method="GET" class="mb-4 no-print">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="report_type">Jenis Laporan</label>
                                <select name="report_type" id="report_type" class="form-control">
                                    <option value="sales_summary" {{ $reportType == 'sales_summary' ? 'selected' : '' }}>
                                        Ringkasan Penjualan</option>
                                    <option value="stock_in_summary"
                                        {{ $reportType == 'stock_in_summary' ? 'selected' : '' }}>Ringkasan Stok Masuk
                                    </option>
                                    <option value="expiring_products"
                                        {{ $reportType == 'expiring_products' ? 'selected' : '' }}>Produk Akan Kadaluarsa
                                    </option>
                                    <option value="stock_flow" {{ $reportType == 'stock_flow' ? 'selected' : '' }}>Laporan
                                        Arus Stok</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Dari Tanggal</label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    value="{{ $startDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">Sampai Tanggal</label>
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                    value="{{ $endDate }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="submit_button">&nbsp;</label>
                                <button type="submit" id="submit_button" class="btn btn-primary w-100">Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </form>

                <hr class="no-print">

                {{-- ========================================================== --}}
                {{-- >> AREA CETAK YANG SUDAH DIPERBAIKI << --}}
                {{-- ========================================================== --}}
                <div class="print-area">
                    {{-- 1. HEADER LAPORAN (Hanya terlihat saat print) --}}
                    <div class="print-header">
                        <img src="{{ asset('template/dist/assets/images/macan.jpg') }}" alt="Logo"
                            class="logo">
                        <div class="header-text">
                            <h1>{{ $reportTitle }}</h1>
                            <p>Klub Nutrisi Berkat Ceria Purwokerto</p>
                            <p class="report-date">Tanggal Laporan: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
                        </div>
                    </div>

                    {{-- 2. KONTEN TABEL (SEMUA TABEL DIKEMBALIKAN DI SINI) --}}

                    {{-- Tabel untuk Ringkasan Penjualan --}}
                    @if ($reportType == 'sales_summary')
                        @if ($reportType == 'sales_summary')
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kode Transaksi</th>
                                        <th>Detail Produk</th>
                                        <th>Total Harga</th>
                                        <th>Profit</th>
                                        <th>Admin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $transaksi)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}
                                            </td>
                                            {{-- SEKARANG KITA BISA MEMBUAT LINK KARENA $transaksi->id SUDAH ADA --}}
                                            <td><a
                                                    href="{{ route('transaksi.show', $transaksi->id) }}">{{ $transaksi->kode_transaksi }}</a>
                                            </td>
                                            <td>
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($transaksi->detailTransaksi as $detail)
                                                        <li><strong>{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</strong>:
                                                            {{ $detail->jumlah }} x
                                                            Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                            <td>Rp{{ number_format($transaksi->profit, 0, ',', '.') }}</td>
                                            <td>{{ $transaksi->user->name ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data penjualan untuk periode
                                                ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                        {{-- Tabel untuk Ringkasan Stok Masuk --}}
                    @elseif ($reportType == 'stock_in_summary')
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Stok Masuk</th>
                                    <th>Supplier</th>
                                    <th>Detail Produk</th>
                                    <th>Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $stok)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($stok->tanggal_masuk)->format('d-m-Y') }}</td>
                                        <td><a
                                                href="{{ route('stok_masuk.show', $stok->id) }}">{{ $stok->kode_stok_masuk }}</a>
                                        </td>
                                        <td>{{ $stok->supplier->nama_supplier ?? 'N/A' }}</td>
                                        <td>
                                            <ul class="list-unstyled mb-0">
                                                @foreach ($stok->detailStokMasuk as $detail)
                                                    <li><strong>{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</strong>:
                                                        {{ $detail->jumlah }}
                                                        {{ $detail->produk->satuan->nama_satuan ?? '' }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $stok->user->name ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data stok masuk untuk periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Tabel untuk Produk Akan Kadaluarsa --}}
                    @elseif ($reportType == 'expiring_products')
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Stok Masuk</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Tanggal Kadaluarsa</th>
                                    <th>Sisa Hari</th>
                                    <th>Supplier</th>
                                    <th>Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $detail)
                                    <tr>
                                        <td>{{ $detail->stokMasuk->kode_stok_masuk ?? 'N/A' }}</td>
                                        <td>{{ $detail->produk->nama_produk ?? 'N/A' }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>{{ $detail->satuan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($detail->tanggal_kadaluarsa)->format('d-m-Y') }}</td>
                                        <td>
                                            @php $diffInDays = \Carbon\Carbon::now()->diffInDays($detail->tanggal_kadaluarsa, false); @endphp
                                            @if ($diffInDays < 0)
                                                <span class="badge bg-danger">Kadaluarsa ({{ abs($diffInDays) }} hari
                                                    lalu)</span>
                                            @elseif ($diffInDays <= 30)
                                                <span class="badge bg-warning">{{ $diffInDays }} hari lagi</span>
                                            @else
                                                <span class="badge bg-info">{{ $diffInDays }} hari lagi</span>
                                            @endif
                                        </td>
                                        <td>{{ $detail->stokMasuk->supplier->nama_supplier ?? 'N/A' }}</td>
                                        <td>{{ $detail->stokMasuk->user->name ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada produk yang akan kadaluarsa dalam 3
                                            bulan ke depan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Tabel untuk Laporan Arus Stok --}}
                    @elseif ($reportType == 'stock_flow')
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>Jenis</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                        <td><a href="{{ $item->route }}">{{ $item->kode }}</a></td>
                                        <td>
                                            @if ($item->jenis == 'Masuk')
                                                <span class="badge bg-success">Masuk</span>
                                            @else
                                                <span class="badge bg-danger">Keluar</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>{{ $item->jumlah }} {{ $item->satuan }}</td>
                                        <td>{{ $item->admin }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada pergerakan stok untuk periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif

                    {{-- 3. BAGIAN TANDA TANGAN (Hanya terlihat saat print) --}}
                    <div class="signature-area">
                        <div class="signature-block">
                            <p>Purwokerto, {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</p>
                            <p>Pemilik Klub Nutrisi</p>
                            <div class="signature-space"></div>
                            <p class="signature-name">Cici Lili</p>
                        </div>
                    </div>

                    {{-- 4. FOOTER LAPORAN (Hanya terlihat saat print) --}}
                    <div class="print-footer">
                        <p>Laporan ini dibuat secara otomatis oleh Sistem Manajemen Stok Barang.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    {{-- Blok @push('styles') ini tidak berubah sama sekali --}}
    <style>
        .print-header,
        .signature-area,
        .print-footer {
            display: none;
        }

        @media print {

            .no-print,
            .pc-sidebar,
            .pc-header,
            .pc-footer,
            .page-header {
                display: none !important;
            }

            body,
            .container,
            .card,
            .card-body {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
            }

            .print-header,
            .signature-area,
            .print-footer {
                display: block;
            }

            .print-header {
                display: flex;
                align-items: center;
                border-bottom: 2px solid #000;
                padding-bottom: 15px;
                margin-bottom: 20px;
            }

            .print-header .logo {
                max-width: 100px;
                margin-right: 0px;
            }

            .print-header .header-text {
                text-align: center;
                flex-grow: 1;
            }

            .print-header h1 {
                margin: 0;
                font-size: 20px;
                font-weight: bold;
            }

            .print-header p {
                margin: 0;
                font-size: 14px;
            }

            .print-header .report-date {
                font-size: 12px;
                color: #555;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }

            th,
            td {
                border: 1px solid #ccc;
                padding: 6px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
                font-weight: bold;
            }

            .badge {
                border: 1px solid #000;
                color: #000 !important;
                background-color: #fff !important;
            }

            .signature-area {
                margin-top: 50px;
                display: flex;
                justify-content: flex-end;
                width: 100%;
            }

            .signature-block {
                text-align: center;
                width: 250px;
            }

            .signature-block p {
                margin: 0;
                font-size: 14px;
            }

            .signature-space {
                height: 70px;
            }

            .signature-name {
                font-weight: bold;
            }

            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 10px;
                color: #888;
                border-top: 1px solid #ccc;
                padding-top: 5px;
            }
        }
    </style>
@endpush
