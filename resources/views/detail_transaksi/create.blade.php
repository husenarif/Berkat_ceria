@extends('layouts.mantis')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h4>Tambah Detail Transaksi</h4></div>
        <div class="card-body">
            <form action="{{ route('detail_transaksi.store') }}" method="POST">
                @csrf

                <div class="form-group my-2">
                    <label for="transaksi_id">Transaksi</label>
                    <select name="transaksi_id" class="form-control">
                        <option value="">-- Pilih Transaksi --</option>
                        @foreach($transaksi as $trx)
                            <option value="{{ $trx->id }}" {{ old('transaksi_id') == $trx->id ? 'selected' : '' }}>
                                {{ $trx->kode_transaksi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group my-2">
                    <label for="produk_id">Produk</label>
                    <select name="produk_id" class="form-control">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produk as $item)
                            <option value="{{ $item->id }}" {{ old('produk_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group my-2">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah') }}">
                </div>

                <div class="form-group my-2">
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" class="form-control" value="{{ old('harga') }}">
                </div>

                <div class="form-group my-2">
                    <label for="total">Total</label>
                    <input type="number" name="total" class="form-control" value="{{ old('total') }}">
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('detail_transaksi.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
