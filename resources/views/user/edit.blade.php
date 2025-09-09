@extends('layouts.mantis')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Edit User</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('user.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <!-- FORM MULAI DI SINI -->
                <form action="{{ route('user.update', $users->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group my-2">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name')
                        is-invalid
                            
                        @enderror"
                            value="{{ $users->name }}" autofocus>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                     <div class="form-group my-2">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email"
                            class="form-control @error('email')
                        is-invalid
                            
                        @enderror"
                            value="{{ $users->email }}" autofocus>
                        @error('email')
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
