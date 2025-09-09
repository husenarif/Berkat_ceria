@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Stok Keluar</h4>
                <div>
                    <a href="{{ route('stok_keluar.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <!-- FORM MULAI DI SINI -->
                <form action="{{ route('stok_keluar.store') }}" method="POST">
                    @csrf

                    <div class="form-group my-2">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk"
                            class="form-control @error('nama_produk')
                        is-invalid
                            
                        @enderror"
                            value="{{ old('nama_produk') }}" autofocus>
                        @error('nama_produk')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="form-group my-2">
                        <label for="kategori_id">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-control">
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
                        <select name="supplier_id" id="supplier_id" class="form-control">
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
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" cols="30" rows="3"
                            class="form-control  @error('deskripsi')
                        is-invalid 
                        @enderror">
                        {{ old('deskripsi') }}
                    </textarea>
                        @error('deskripsi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                            class="form-control  @error('tanggal_masuk')
                        is-invalid
                            
                        @enderror"
                            value="{{ old('tanggal_masuk') }}" autofocus>
                        @error('tanggal_masuk')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="tanggal_kadaluarsa">Kadaluarsa</label>
                        <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa"
                            class="form-control  @error('tanggal_kadaluarsa')
                        is-invalid
                            
                        @enderror"
                            value="{{ old('tanggal_kadaluarsa') }}" autofocus>
                        @error('tanggal_kadaluarsa')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group my-2">
                        <label for="satuan">Satuan</label>
                        <input type="text" name="satuan" id="satuan"
                            class="form-control  @error('satuan')
                        is-invalid
                            
                        @enderror"
                            value="{{ old('satuan') }}" autofocus>
                        @error('satuan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group my-2">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah"
                            class="form-control  @error('jumlah')
                        is-invalid
                            
                        @enderror"
                            value="{{ old('jumlah') }}" autofocus>
                        @error('jumlah')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- TOMBOL SUBMIT -->
                    <div class="my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
                <!-- FORM BERAKHIR DI SINI -->
            </div>
        </div>
    </div>
@endsection
