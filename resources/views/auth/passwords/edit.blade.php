@extends('layouts.mantis')

@section('content')
    <div class="container">
        <h4>Ganti Password</h4>
       <div class="d-flex justify-content-end">
    {{-- Periksa apakah pengguna yang sedang login memiliki role_id == 1 (Pemilik) --}}
    @if (auth()->user()->role_id == 1)
        {{-- Jika Pemilik, arahkan ke halaman daftar pengguna --}}
        <a class="btn btn-danger" href="{{ route('user.index') }}">Kembali</a>
    @else
        {{-- Jika bukan Pemilik (misalnya Admin), arahkan ke halaman dashboard --}}
        <a class="btn btn-danger" href="{{ route('home') }}">Kembali</a>
    @endif
</div>


        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.update.new') }}">
            @csrf

            <div class="mb-3">
                <label>Password Lama</label>
                <input type="password" name="current_password"
                    class="form-control @error('current_password') is-invalid @enderror" required>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Password Baru</label>
                <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror"
                    required>
                @error('new_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>

            <button class="btn btn-primary" type="submit">Ubah Password</button>
        </form>
    </div>
@endsection
