@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Data Produk</h4>
                {{-- PERBAIKAN TATA LETAK TOMBOL DI SINI --}}
                <div class="d-flex flex-column align-items-end">
                    <a class="btn btn-primary mb-2" href="{{ route('produk.create') }}">Tambah Data</a>
                    <a class="btn btn-info" href="{{ route('stok_masuk.expiring_soon') }}">Produk Akan Kadaluarsa</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="myTable"> {{-- Sesuaikan ID tabel jika Anda menggunakan DataTables --}}
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">No</th>
                            <th class="text-center text-nowrap">Kode Produk</th>
                            <th class="text-center text-nowrap">Nama Produk</th>
                            <th class="text-center text-nowrap">Kategori</th>
                            <th class="text-center text-nowrap">Satuan</th>
                            <th class="text-center text-nowrap">Harga Produk</th>
                            <th class="text-center text-nowrap">Harga Modal</th>
                            <th class="text-center text-nowrap">Stok</th>
                            <th class="text-center text-nowrap">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produk as $index => $item)
                            <tr>
                                <td class="text-center text-nowrap">{{ $index + 1 }}</td>
                                <td>
                                    <a href="{{ route('produk.show', $item->id) }}" class="fw-bold">
                                        {{ $item->kode_produk }}
                                    </a>
                                </td>
                                <td class="text-nowrap">{{ $item->nama_produk }}</td>
                                <td class="text-nowrap">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td class="text-center text-nowrap">{{ $item->satuan->nama_satuan ?? '-' }}</td>
                                <td class="text-nowrap">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="text-nowrap">Rp{{ number_format($item->modal_harga, 0, ',', '.') }}</td>

                                <td class="text-center text-nowrap">{{ $item->stok }}</td>
                                {{-- <td class="text-center text-nowrap"> --}}
                                <td class="text-center text-nowrap">
                                    <div class="dropdown">
                                        <button style="background-color: #58c058 " class="btn btn-secondary dropdown-toggle"
                                            type="button" id="dropdownMenuButton{{ $item->id }}"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Aksi
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('produk.edit', $item->id) }}">Edit</a>
                                            </li>
                                            <li><a class="dropdown-item text-danger"
                                                    href="{{ route('produk.destroy', $item->id) }}"
                                                    data-confirm-delete="true">Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty {{-- Tambahkan blok @empty ini --}}
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data produk.</td> {{-- colspan 9 karena ada 9 kolom --}}
                            </tr>
                        @endforelse {{-- Tutup dengan @endforelse --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- TAMBAHKAN BLOK SCRIPT INI --}}
    @push('scripts')
        @if ($produk->isNotEmpty())
            {{-- Opsional: hanya inisialisasi jika ada data --}}
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable();
                });
            </script>
        @endif
    @endpush
@endsection
