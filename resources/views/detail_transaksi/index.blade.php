{{-- resources/views/transaksi/detail_index.blade.php --}}
{{-- Asumsikan ini adalah halaman yang menampilkan daftar detail transaksi --}}

@extends('layouts.mantis') {{-- Sesuaikan dengan layout Anda --}}

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Data Detail Transaksi</h4>
                <div>
                    {{-- Tombol Tambah Detail (jika ada) --}}
                    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                        Tambah Detail
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success my-2">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger my-2">{{ session('error') }}</div>
                @endif

                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">No</th>
                            <th class="text-center text-nowrap">Kode Transaksi</th>
                            <th class="text-center text-nowrap">Produk</th>
                            <th class="text-center text-nowrap">Jumlah</th>
                            <th class="text-center text-nowrap">Subtotal Item</th> {{-- Ini adalah subtotal per item --}}
                            <th class="text-center text-nowrap">Total Harga Transaksi</th> {{-- Ini dari transaksi utama --}}
                            <th class="text-center text-nowrap">Bayar Transaksi</th> {{-- Ini dari transaksi utama --}}
                            <th class="text-center text-nowrap">Profit Transaksi</th> {{-- Ini dari transaksi utama --}}
                            <th class="text-center text-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Pastikan controller mengirimkan $detailTransaksi ke view ini --}}
                        @foreach ($detail_transaksi as $item)
                            <tr>
                                <td class="text-center text-nowrap">{{ $loop->iteration }}</td>
                                <td>{{ $item->transaksi->kode_transaksi }}</td>
                                <td>{{ $item->produk->nama_produk }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                {{-- Mengakses total_harga, bayar, kembalian dari relasi transaksi --}}
                                <td>Rp {{ number_format($item->transaksi->total_harga, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->transaksi->bayar, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->transaksi->kembalian, 0, ',', '.') }}</td>
                                <td class="text-center text-nowrap">
                                    <div class="dropdown">
                                        <a class="btn dropdown-toggle" href="#" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Aksi
                                        </a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                {{-- Tombol Edit Detail (jika ada route dan methodnya) --}}
                                                <a class="dropdown-item"
                                                    href="{{ route('transaksi.edit', $item->transaksi->id) }}">Edit
                                                    Detail</a>
                                            </li>
                                            <li>
                                                {{-- Tombol Hapus Detail Transaksi Individual --}}
                                             <li><a class="dropdown-item text-danger"
                                                    href="{{ route('transaksi.destroy', $item->id) }}"
                                                    data-confirm-delete="true">Hapus</a></li>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
       {{-- TAMBAHKAN BLOK SCRIPT INI --}}
    @push('scripts')
        @if($detail_transaksi->isNotEmpty()) {{-- Opsional: hanya inisialisasi jika ada data --}}
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable();
                });
            </script>
        @endif
    @endpush
@endsection
