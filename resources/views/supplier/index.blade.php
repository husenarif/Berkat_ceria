@extends('layouts.mantis')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-item-center">
                <h4 class="card-title">Data supplier</h4>
                <div>
                    <a href="{{ route('supplier.create') }}" class="btn btn-primary ">
                        Tambah Data
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">No</th>
                            {{-- <th>Id</th> --}}
                            <th class="text-center text-nowrap">Gudang Asal </th>
                            <th class="text-center text-nowrap">Alamat</th>
                            <th class="text-center text-nowrap">Telepon</th>
                            {{-- <th class="text-center text-nowrap">Email</th> --}}
                            <th class="text-center text-nowrap">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supplier as $index => $item)
                            <tr>
                                <td class="text-center text-nowrap">{{ $index + 1 }}</td>
                                {{-- <td>{{ $item->supplier_id }}</td> --}}
                                <td>{{ $item->nama_supplier }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td class="text-start">{{ $item->telepon }}</td>
                                {{-- <td>{{ $item->email }}</td> --}}
                                <td class="text-center text-nowrap">
                                    <div class="dropdown">
                                           <button  style="background-color: #58c058 " class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            Aksi
                                        </button>


                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('supplier.edit', $item->id)}}">Edit</a></li>
                                             <li><a class="dropdown-item text-danger"
                                                    href="{{ route('supplier.destroy', $item->id) }}"
                                                    data-confirm-delete="true">Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                         @empty {{-- Tambahkan blok @empty ini --}}
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data supplier.</td> {{-- colspan 9 karena ada 9 kolom --}}
                            </tr>
                        @endforelse {{-- Tutup dengan @endforelse --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
       {{-- TAMBAHKAN BLOK SCRIPT INI --}}
    @push('scripts')
        @if($supplier->isNotEmpty()) {{-- Opsional: hanya inisialisasi jika ada data --}}
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable();
                });
            </script>
        @endif
    @endpush
@endsection
