@extends('layouts.mantis')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-item-center">
                <h4 class="card-title">Satuan Produk</h4>
                <div>
                    <a href="{{ route('satuan_produk.create') }}" class="btn btn-primary ">
                        Tambah Data
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">No</th>
                            <th class="text-center text-nowrap">Nama Satuan</th>
                            <th class="text-center text-nowrap">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($satuan_produk as $index => $item)
                            <tr>
                                <td class="text-center text-nowrap">{{ $index + 1 }}</td>
                                <td>{{ $item->nama_satuan ?? '-' }}</td>
                                <td class="text-center text-nowrap">

                                    <div class="dropdown">
                                      <button  style="background-color: #58c058 " class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            Aksi
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('satuan_produk.edit', $item->id) }}">Edit</a></li>
                                            <li>

                                            <li><a class="dropdown-item text-danger"
                                                    href="{{ route('satuan_produk.destroy', $item->id) }}"
                                                    data-confirm-delete="true">Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                         @empty {{-- Tambahkan blok @empty ini --}}
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data satuan produk.</td> {{-- colspan 9 karena ada 9 kolom --}}
                            </tr>
                        @endforelse {{-- Tutup dengan @endforelse --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
       {{-- TAMBAHKAN BLOK SCRIPT INI --}}
    @push('scripts')
        @if($satuan_produk->isNotEmpty()) {{-- Opsional: hanya inisialisasi jika ada data --}}
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable();
                });
            </script>
        @endif
    @endpush
@endsection
