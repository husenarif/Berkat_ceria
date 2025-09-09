@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Form Stok Barang</h4>
            <div>
                <a href="{{ route('stok_barang.index') }}">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('stok_barang.store') }}" method="POST">
                @csrf

                <div class="form-group my-2">
                    <label for="nama_produk">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" value="{{ old('nama_produk') }}">
                    @error('nama_produk')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="kategori_id">Kategori</label>
                    <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="supplier_id">Supplier</label>
                    <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach ($supplier as $item)
                            <option value="{{ $item->id }}" {{ old('supplier_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="satuan">Satuan</label>
                    <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" value="{{ old('satuan') }}">
                    @error('satuan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="jumlah_produk">Jumlah Produk</label>
                    <input type="number" name="jumlah_produk" class="form-control @error('jumlah_produk') is-invalid @enderror" value="{{ old('jumlah_produk') }}">
                    @error('jumlah_produk')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="tanggal_masuk">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk') }}">
                    @error('tanggal_masuk')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group my-2">
                    <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa</label>
                    <input type="date" name="tanggal_kadaluarsa" class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" value="{{ old('tanggal_kadaluarsa') }}">
                    @error('tanggal_kadaluarsa')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="my-3 text-end">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
