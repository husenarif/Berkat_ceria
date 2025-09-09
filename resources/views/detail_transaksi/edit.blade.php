@extends('layouts.mantis')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h4>Edit Detail Transaksi</h4></div>
        <div class="card-body">
            <form action="{{ route('detail_transaksi.update', $detail_transaksi->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="form-group my-2">
                    <label for="transaksi_id">Transaksi</label>
                    <select name="transaksi_id" class="form-control">
                        <option value="">-- Pilih Transaksi --</option>
                        @foreach($transaksi as $trx)
                            <option value="{{ $trx->id }}" {{ $detail_transaksi->transaksi_id == $trx->id ? 'selected' : '' }}>
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
                            <option value="{{ $item->id }}" {{ $detail_transaksi->produk_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group my-2">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" value="{{ $detail_transaksi->jumlah }}">
                </div>

                <div class="form-group my-2">
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" class="form-control" value="{{ $detail_transaksi->harga }}">
                </div>

                <div class="form-group my-2">
                    <label for="total">Total</label>
                    <input type="number" name="total" class="form-control" value="{{ $detail_transaksi->total }}">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('detail_transaksi.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
