@extends('layouts.mantis')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Edit Supplier</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('supplier.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <!-- FORM MULAI DI SINI -->
                <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group my-2">
                        <label for="nama_supplier">Nama supplier</label>
                        <input type="text" name="nama_supplier" id="nama_supplier"
                            class="form-control @error('nama_supplier')
                        is-invalid
                            
                        @enderror"
                            value="{{ $supplier->nama_supplier }}" autofocus>
                        @error('nama_supplier')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="form-group my-2">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" id="alamat"
                            class="form-control @error('alamat')
                        is-invalid
                            
                        @enderror"
                            value="{{$supplier->alamat}}" autofocus>
                        @error('alamat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>


                    <div class="form-group my-2">
                        <label for="telepon">telephone</label>
                        <input type="number" name="telepon" id="telepon"
                            class="form-control @error('telepon')
                        is-invalid
                            
                        @enderror"
                            value="{{$supplier->telepon}}" autofocus>
                        @error($supplier->telepon)
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    {{-- <div class="form-group my-2">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email"
                            class="form-control @error('email')
                        is-invalid
                            
                        @enderror"
                            value="{{$supplier->email }}" autofocus>
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
