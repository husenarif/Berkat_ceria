
@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center no-print">
                <h4 class="card-title">Data Stok Masuk</h4>
                <div class="d-flex flex-column align-items-end">
                    <a class="btn btn-primary mb-2" href="{{ route('stok_masuk.create') }}">Tambah Data</a>
                    <a class="btn btn-info mb-2" href="{{ route('stok_masuk.expiring_soon') }}">Produk Akan Kadaluarsa</a>
                    <button class="btn btn-secondary" onclick="window.print()">Cetak Laporan</button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Kode Stok Masuk</th>
                            <th>Tanggal Masuk</th>
                            <th>Supplier</th>
                            {{-- KOLOM BARU YANG LEBIH INFORMATIF --}}
                            <th>Produk Terlibat</th>
                            <th class="text-center">Admin</th>
                            <th class="text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokMasuk as $sm)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    {{-- Jadikan Kode Stok Masuk sebagai link ke detailnya --}}
                                    <a href="{{ route('stok_masuk.show', $sm->id) }}" class="fw-bold">{{ $sm->kode_stok_masuk }}</a>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($sm->tanggal_masuk)->format('d-m-Y') }}</td>
                                <td>{{ $sm->supplier->nama_supplier ?? 'N/A' }}</td>
                                
                                {{-- ================================================================== --}}
                                {{-- >> INILAH BAGIAN PENTINGNYA << --}}
                                {{-- Menampilkan daftar produk yang bisa diklik --}}
                                {{-- ================================================================== --}}
                                <td>
                                    @if($sm->detailStokMasuk->isNotEmpty())
                                        {{-- Loop melalui setiap produk dalam batch stok masuk ini --}}
                                        @foreach($sm->detailStokMasuk as $detail)
                                            <a href="{{ route('produk.show', $detail->produk_id) }}" class="d-block mb-1 text-nowrap">
                                                {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }} 
                                                <span class="badge bg-light-primary text-primary">+{{ $detail->jumlah }}</span>
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Tidak ada detail produk.</span>
                                    @endif
                                </td>

                                <td class="text-center">{{ $sm->user->name ?? 'N/A' }}</td>
                                <td class="text-center no-print">
                                    <div class="dropdown">
                                        <button  style="background-color: #58c058 " class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $sm->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            Aksi
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $sm->id }}">
                                            <li><a class="dropdown-item" href="{{ route('stok_masuk.show', $sm->id) }}">Detail</a></li>
                                            <li><a class="dropdown-item" href="{{ route('stok_masuk.edit', $sm->id) }}">Edit</a></li>
                                            <li><a class="dropdown-item text-danger"
                                                    href="{{ route('stok_masuk.destroy', $sm->id) }}"
                                                    data-confirm-delete="true">Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data stok masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- Bagian @push('scripts') dan @push('styles') tidak berubah, biarkan seperti adanya --}}
@push('scripts')
    @if($stokMasuk->isNotEmpty())
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
            });
        </script>
    @endif
@endpush

@push('styles')
<style>
    @media print {
        .no-print, .pc-sidebar, .pc-header, .pc-footer, .page-header, .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate {
            display: none !important;
        }
        body { margin: 0; padding: 0; }
        .pc-container, .pc-content, .card, .card-body { padding: 0 !important; margin: 0 !important; box-shadow: none !important; border: none !important; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    }
</style>
@endpush