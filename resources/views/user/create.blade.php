@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Tambah User</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('user.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf


                    <div class="form-group my-2">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    

                    <div class="form-group my-2">
                        <label for="role_id">Role</label>
                        <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->role_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group my-2">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group my-2">
                        <label for="password">Password</label>
                        <input type="text" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- <div class="form-group my-2">
                        <label for="password_confirmation">Konfirmasi Password:</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div> --}}

                    <!-- Tambahkan form input produk dan jumlah disini jika ingin -->
                    <!-- Misalnya, bisa pakai JavaScript untuk input dinamis -->

                    <div class="my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection



{{-- <div class="container">
    <h1>Tambah Pengguna</h1>

    <form action="{{ route('user.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name">Nama:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Konfirmasi Password:</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection --}}
