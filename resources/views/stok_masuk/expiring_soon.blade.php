@extends('layouts.mantis') {{-- Sesuaikan dengan layout Anda --}}

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Produk Akan Kadaluarsa (Dalam 3 Bulan)</h4>
                <div>
                    <a class="btn btn-primary" href="{{ route('stok_masuk.index') }}">Kembali ke Stok Masuk</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="myTable"> {{-- Sesuaikan ID tabel jika Anda menggunakan DataTables --}}
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode Stok Masuk</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Tanggal Kadaluarsa</th>
                            <th class="text-center">Sisa Hari</th>
                            <th class="text-center">Supplier</th>
                            <th class="text-center">Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expiringProducts as $detail)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $detail->stokMasuk->kode_stok_masuk ?? 'N/A' }}</td>
                                <td>{{ $detail->produk->nama_produk ?? 'N/A' }}</td>
                                <td class="text-center">{{ $detail->jumlah }}</td>
                                <td class="text-center">{{ $detail->satuan }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($detail->tanggal_kadaluarsa)->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    @php
                                        $diffInDays = \Carbon\Carbon::now()->diffInDays($detail->tanggal_kadaluarsa, false);
                                    @endphp
                                    @if ($diffInDays < 0)
                                        <span class="badge bg-danger">Kadaluarsa ({{ abs($diffInDays) }} hari lalu)</span>
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
                                <td colspan="9" class="text-center">Tidak ada produk yang akan kadaluarsa dalam 3 bulan ke depan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
