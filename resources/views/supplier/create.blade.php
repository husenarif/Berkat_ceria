@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Data Supplier</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('supplier.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <!-- FORM MULAI DI SINI -->
                <form action="{{ route('supplier.store') }}" method="POST">
                    @csrf



                   <div class="form-group my-2">
                        <label for="nama_supplier">Nama Supplier</label>
                        <input type="text" name="nama_supplier" id="nama_supplier"
                            class="form-control @error('nama_supplier')
                        is-invalid
                            
                        @enderror"
                            value="{{ old('nama_supplier') }}" autofocus>
                        @error('nama_supplier')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>
                   

                    <div class="form-group my-2">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" cols="30" rows="3"
                            class="form-control  @error('alamat')
                        is-invalid 
                        @enderror">
                        {{ old('alamat') }}
                    </textarea>
                        @error('alamat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                     <div class="form-group my-2">
                        <label for="telepon">Telephone</label>
                        <textarea name="telepon" id="telepon" cols="30" rows="3"
                            class="form-control  @error('telepon')
                        is-invalid 
                        @enderror">
                        {{ old('telepon') }}
                    </textarea>
                        @error('telepon')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                     {{-- <div class="form-group my-2">
                        <label for="email">E-mail</label>
                        <textarea name="email" id="email" cols="30" rows="3"
                            class="form-control  @error('email')
                        is-invalid 
                        @enderror">
                        {{ old('email') }}
                    </textarea>
                        @error('email')
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
