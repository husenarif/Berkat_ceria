@extends('layouts.mantis')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Data Produk</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('produk.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <!-- FORM MULAI DI SINI -->
                <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- TAMBAHKAN INI: Input Kode Produk --}}
                    <div class="form-group my-2">
                        <label for="kode_produk">Kode Produk</label>
                        <input type="text" name="kode_produk" id="kode_produk"
                            class="form-control @error('kode_produk') is-invalid @enderror"
                            value="{{ old('kode_produk', $kode_produk) }}" readonly> {{-- readonly karena auto-generated --}}
                        @error('kode_produk')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk"
                            class="form-control @error('nama_produk') is-invalid @enderror" value="{{ old('nama_produk') }}"
                            autofocus>
                        @error('nama_produk')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="kategori_id">Kategori</label> {{-- Perbaikan label --}}
                        <select name="kategori_id" id="kategori_id"
                            class="form-control @error('kategori_id') is-invalid @enderror"> {{-- Perbaikan name dan id --}}
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                    {{-- Perbaikan old() --}}
                                    {{ $item->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            {{-- Perbaikan error --}}
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="satuan_id">Satuan</label> {{-- Perbaikan label --}}
                        <select name="satuan_id" id="satuan_id"
                            class="form-control @error('satuan_id') is-invalid @enderror"> {{-- Perbaikan name dan id --}}
                            <option value="">-- Pilih Satuan --</option>
                            @foreach ($satuan as $item)
                                <option value="{{ $item->id }}" {{ old('satuan_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_satuan }}
                                </option>
                            @endforeach
                        </select>
                        @error('satuan_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="harga">Harga</label>
                        <input type="text" name="harga" id="harga"
                            class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}"
                            autofocus>
                        @error('harga')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    {{-- TAMBAHKAN INI UNTUK MODAL HARGA --}}
                    <div class="form-group my-2">
                        <label for="modal_harga">Harga Modal</label>
                        <input type="text" name="modal_harga" id="modal_harga"
                            class="form-control @error('modal_harga') is-invalid @enderror"
                            value="{{ old('modal_harga') }}" autofocus>
                        @error('modal_harga')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="gambar">Gambar Produk</label>
                        <input type="file" name="gambar" id="gambar"
                            class="form-control @error('gambar') is-invalid @enderror">
                        @error('gambar')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        {{-- Untuk form edit, tampilkan gambar saat ini --}}
                        @if (isset($produk) && $produk->gambar)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="Gambar Produk"
                                    style="width: 150px;">
                            </div>
                        @endif
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
