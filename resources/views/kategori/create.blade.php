@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Data Kategori</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('kategori.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <!-- FORM MULAI DI SINI -->
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                   <div class="form-group my-2">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori"
                            class="form-control @error('nama_kategori')
                        is-invalid
                            
                        @enderror"
                            value="{{ old('nama_kategori') }}" autofocus>
                        @error('nama_kategori')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>
                   
{{-- 
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
                    </div> --}}

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
