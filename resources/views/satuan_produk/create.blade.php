@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Data Satuan</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('satuan_produk.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <!-- FORM MULAI DI SINI -->
                <form action="{{ route('satuan_produk.store') }}" method="POST">
                    @csrf

                    <div class="form-group my-2">
                        <label for="nama_produk">Nama Satuan</label>
                        <input type="text" name="nama_satuan" id="nama_satuan"
                            class="form-control @error('nama_satuan')
                        is-invalid
                            
                        @enderror"
                            value="{{ old('nama_satuan') }}" autofocus>
                        @error('nama_satuan')
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
