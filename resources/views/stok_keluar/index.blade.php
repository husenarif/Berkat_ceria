@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-item-center">
                <h4 class="card-title">Data Stok Masuk</h4>
                <div>
                    <a href="{{ route('stok_keluar.create') }}" class="btn btn-primary ">
                        Tambah Data
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Id</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Supplier</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Kadaluarsa</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stok_keluar as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->produk_id }}</td>
                                <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                                <td>{{ $item->deskripsi }}</td>
                                <td>{{ $item->tanggal_masuk }}</td>
                                <td>{{ $item->tanggal_kadaluarsa }}</td>
                                <td>{{ $item->satuan }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
