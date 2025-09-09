@extends('layouts.mantis')

@section('content')
    <div class="container"> {{-- Saya menambahkan class container agar lebih rapi --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Edit Produk</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('produk.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                {{-- ================================================================== --}}
                {{-- >> FORM YANG SUDAH DISESUAIKAN << --}}
                {{-- ================================================================== --}}
                <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Kode Produk (readonly) --}}
                    <div class="form-group my-2">
                        <label for="kode_produk">Kode Produk</label>
                        <input type="text" name="kode_produk" id="kode_produk"
                            class="form-control"
                            value="{{ $produk->kode_produk }}" readonly>
                    </div>

                    {{-- Nama Produk --}}
                    <div class="form-group my-2">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk"
                            class="form-control @error('nama_produk') is-invalid @enderror"
                            value="{{ old('nama_produk', $produk->nama_produk) }}" autofocus>
                        @error('nama_produk')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="form-group my-2">
                        <label for="kategori_id">Kategori</label>
                        <select name="kategori_id" id="kategori_id"
                            class="form-control @error('kategori_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('kategori_id', $produk->kategori_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Satuan --}}
                    <div class="form-group my-2">
                        <label for="satuan_id">Satuan</label>
                        <select name="satuan_id" id="satuan_id"
                            class="form-control @error('satuan_id') is-invalid @enderror">
                            <option value="">-- Pilih Satuan --</option>
                            @foreach ($satuan as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('satuan_id', $produk->satuan_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_satuan }}
                                </option>
                            @endforeach
                        </select>
                        @error('satuan_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Harga Jual --}}
                    <div class="form-group my-2">
                        <label for="harga">Harga Jual</label>
                        <input type="number" name="harga" id="harga"
                            class="form-control @error('harga') is-invalid @enderror"
                            value="{{ old('harga', $produk->harga) }}">
                        @error('harga')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Harga Modal --}}
                    <div class="form-group my-2">
                        <label for="modal_harga">Harga Modal</label>
                        <input type="number" name="modal_harga" id="modal_harga"
                            class="form-control @error('modal_harga') is-invalid @enderror"
                            value="{{ old('modal_harga', $produk->modal_harga) }}">
                        @error('modal_harga')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ================================================================== --}}
                    {{-- >> BAGIAN GAMBAR YANG SUDAH DISEMPURNAKAN << --}}
                    {{-- ================================================================== --}}
                    <div class="form-group my-2">
                        <label for="gambar">Ganti Gambar Produk (Opsional)</label>
                        <input type="file" name="gambar" id="gambar"
                            class="form-control @error('gambar') is-invalid @enderror">
                        @error('gambar')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        
                        {{-- Tampilkan gambar saat ini jika ada --}}
                        @if ($produk->gambar)
                            <div class="mt-3">
                                <p class="text-muted mb-1">Gambar Saat Ini:</p>
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="Gambar Produk"
                                    class="img-thumbnail" style="width: 150px; height: auto;">
                            </div>
                        @else
                            <p class="text-muted mt-2">Belum ada gambar untuk produk ini.</p>
                        @endif
                    </div>

                    <!-- TOMBOL SUBMIT -->
                    <div class="my-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
