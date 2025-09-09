@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Edit Stok Masuk</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('stok_masuk.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('stok_masuk.update', $stokMasuk->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Bagian Utama Form (tidak ada perubahan signifikan) --}}
                    <div class="form-group my-2">
                        <label for="kode_stok_masuk">Kode Stok Masuk</label>
                        <input type="text" name="kode_stok_masuk" class="form-control"
                            value="{{ old('kode_stok_masuk', $stokMasuk->kode_stok_masuk) }}" readonly>
                    </div>
                    <div class="form-group my-2">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" class="form-control"
                            value="{{ old('tanggal_masuk', \Carbon\Carbon::parse($stokMasuk->tanggal_masuk)->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group my-2">
                        <label for="supplier_id">Supplier</label>
                        <select name="supplier_id" id="select-supplier" class="form-control">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($supplier as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('supplier_id', $stokMasuk->supplier_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group my-2">
                        <label for="user_id">Admin</label>
                        <input type="text" class="form-control" value="{{ $stokMasuk->user->name ?? auth()->user()->name }}" readonly>
                        <input type="hidden" name="user_id" value="{{ $stokMasuk->user_id ?? auth()->id() }}">
                    </div>
                    <div class="form-group my-2">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $stokMasuk->deskripsi) }}</textarea>
                    </div>

                    <hr>
                    <h5 class="mb-3">Detail Produk Masuk</h5>

                    {{-- ========================================================== --}}
                    {{-- >> PEROMBAKAN TOTAL BAGIAN DETAIL PRODUK DIMULAI DI SINI << --}}
                    {{-- ========================================================== --}}
                    <div id="detail-produk-container">
                        @foreach ($stokMasuk->detailStokMasuk as $index => $detail)
                            <div class="card produk-item mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-sm btn-danger btn-remove">Hapus</button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label>Produk</label>
                                            <select name="produk_id[]" class="form-control select2-produk" required>
                                                <option></option>
                                                @foreach ($produk as $p)
                                                    <option value="{{ $p->id }}"
                                                        data-modal="{{ $p->modal_harga }}"
                                                        data-jual="{{ $p->harga }}"
                                                        {{ $detail->produk_id == $p->id ? 'selected' : '' }}>
                                                        {{ $p->nama_produk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label>Jumlah</label>
                                            <input type="number" name="jumlah[]" class="form-control"
                                                placeholder="Jumlah" value="{{ $detail->jumlah }}" required>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label>Satuan</label>
                                            <select name="satuan[]" class="form-control select2-satuan" required>
                                                <option></option>
                                                @foreach ($satuan as $s)
                                                    <option value="{{ $s->id }}"
                                                        {{ $detail->satuan == $s->nama_satuan ? 'selected' : '' }}>
                                                        {{ $s->nama_satuan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- Baris untuk input harga & gambar --}}
                                    <div class="row">
                                        <div class="col-md-4 form-group mb-3">
                                            <label>Harga Modal</label>
                                            <input type="number" name="harga_modal_satuan[]" class="form-control"
                                                placeholder="Harga Modal" value="{{ $detail->harga_modal_satuan }}" required>
                                        </div>
                                        <div class="col-md-4 form-group mb-3">
                                            <label>Harga Jual</label>
                                            {{-- Kita ambil harga jual dari relasi produk --}}
                                            <input type="number" name="harga_jual[]" class="form-control"
                                                placeholder="Harga Jual" value="{{ $detail->produk->harga ?? 0 }}">
                                        </div>
                                        <div class="col-md-4 form-group mb-3">
                                            <label>Gambar Produk (Opsional)</label>
                                            <input type="file" name="gambar[]" class="form-control">
                                            @if($detail->produk->gambar)
                                                <small>Gambar saat ini: <a href="{{ asset('storage/' . $detail->produk->gambar) }}" target="_blank">Lihat</a></small>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Baris untuk tanggal kadaluarsa --}}
                                    <div class="form-group">
                                        <label>Tanggal Kadaluarsa</label>
                                        <input type="date" name="tanggal_kadaluarsa[]" class="form-control"
                                            value="{{ $detail->tanggal_kadaluarsa ? \Carbon\Carbon::parse($detail->tanggal_kadaluarsa)->format('Y-m-d') : '' }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="tambah-produk" class="btn btn-secondary mb-3">Tambah Produk</button>

                    <div class="my-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            function initBasicSelect2(selector, placeholder) {
                $(selector).select2({
                    placeholder: placeholder,
                    theme: 'bootstrap-5',
                    tags: true,
                    createTag: function(params) {
                        var term = $.trim(params.term);
                        if (term === '') return null;
                        return { id: term, text: term, new: true };
                    }
                });
            }

            function initProdukSelect2(selector) {
                $(selector).select2({
                    placeholder: '-- Pilih Produk --',
                    theme: 'bootstrap-5',
                    tags: false
                }).on('select2:select', function(e) {
                    var cardBody = $(this).closest('.card-body');
                    var selectedOption = $(e.target).find('option:selected');
                    var modalPrice = selectedOption.data('modal') || 0;
                    var jualPrice = selectedOption.data('jual') || 0;
                    cardBody.find('input[name="harga_modal_satuan[]"]').val(modalPrice);
                    cardBody.find('input[name="harga_jual[]"]').val(jualPrice);
                });
            }

            initBasicSelect2('#select-supplier', '-- Pilih atau ketik supplier baru --');

            // ==========================================================
            // >> PERBAIKAN 3: LOGIKA JAVASCRIPT YANG BENAR <<
            // ==========================================================
            $('#tambah-produk').on('click', function() {
                const container = $('#detail-produk-container');
                const produkOptionsData = @json($produk);
                const satuanOptionsData = @json($satuan);

                let selectProdukOptions = '<option></option>';
                produkOptionsData.forEach(p => {
                    selectProdukOptions += `<option value="${p.id}" data-modal="${p.modal_harga}" data-jual="${p.harga}">${p.nama_produk}</option>`;
                });

                let selectSatuanOptions = '<option></option>';
                satuanOptionsData.forEach(s => {
                    selectSatuanOptions += `<option value="${s.id}">${s.nama_satuan}</option>`;
                });

                // INI ADALAH TEMPLATE YANG BENAR: HANYA SATU KARTU BARU
                const newCardHtml = `
                <div class="card produk-item mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-danger btn-remove">Hapus</button>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Produk</label>
                                <select name="produk_id[]" class="form-control select2-produk" required>${selectProdukOptions}</select>
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label>Satuan</label>
                                <select name="satuan[]" class="form-control select2-satuan" required>${selectSatuanOptions}</select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label>Harga Modal</label>
                                <input type="number" name="harga_modal_satuan[]" class="form-control" placeholder="Harga Modal" required>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>Harga Jual</label>
                                <input type="number" name="harga_jual[]" class="form-control" placeholder="Harga Jual" required>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>Gambar Produk (Opsional)</label>
                                <input type="file" name="gambar[]" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa[]" class="form-control">
                        </div>
                    </div>
                </div>
                `;

                const newCard = $(newCardHtml);
                container.append(newCard);

                initProdukSelect2(newCard.find('.select2-produk'));
                initBasicSelect2(newCard.find('.select2-satuan'), '-- Pilih atau ketik satuan baru --');
            });

            $('#detail-produk-container').on('click', '.btn-remove', function() {
                $(this).closest('.produk-item').remove();
            });

            $('.produk-item').each(function() {
                initProdukSelect2($(this).find('.select2-produk'));
                initBasicSelect2($(this).find('.select2-satuan'), '-- Pilih atau ketik satuan baru --');
            });
        });
    </script>
@endpush
