@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Tambah data</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('stok_masuk.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('stok_masuk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Field Stok Masuk Utama --}}
                    <div class="form-group my-2">
                        <label for="kode_stok_masuk">Kode Stok Masuk</label>
                        <input type="text" name="kode_stok_masuk" class="form-control" value="{{ old('kode_stok_masuk', $kode_stok_masuk) }}" readonly>
                    </div>
                    <div class="form-group my-2">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', date('Y-m-d')) }}">
                    </div>
                    <div class="form-group my-2">
                        <label for="supplier_id">Supplier</label>
                        <select name="supplier_id" id="select-supplier" class="form-control">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($supplier as $item)
                                <option value="{{ $item->id }}" {{ old('supplier_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group my-2">
                        <label for="user_id">Admin</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    </div>
                    <div class="form-group my-2">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control">{{ old('deskripsi') }}</textarea>
                    </div>

                    <hr>
                    <h5 class="mb-3">Detail Produk Masuk</h5>

                    <div id="detail-produk-container">
                        {{-- Logika 'old' tidak perlu diubah secara signifikan --}}
                    </div>

                    <button type="button" id="tambah-produk" class="btn btn-secondary mb-3">Tambah Produk</button>

                    <div class="my-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
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
                    placeholder: '-- Pilih atau ketik produk baru --',
                    theme: 'bootstrap-5',
                    tags: true,
                    createTag: function(params) {
                        var term = $.trim(params.term);
                        if (term === '') return null;
                        return { id: term, text: term, new: true };
                    }
                }).on('select2:select', function(e) {
                    var cardBody = $(this).closest('.card-body');
                    var data = e.params.data;
                    var isNew = data.new || false;
                    var conditionalRow = cardBody.find('.conditional-inputs');

                    conditionalRow.toggle(isNew);
                    conditionalRow.find('input, select').prop('required', isNew);

                    if (!isNew) {
                        var selectedOption = $(e.target).find('option:selected');
                        var modalPrice = selectedOption.data('modal') || 0;
                        var satuanId = selectedOption.data('satuan-id') || '';
                        cardBody.find('input[name="harga_modal_satuan[]"]').val(modalPrice);
                        cardBody.find('select[name="satuan[]"]').val(satuanId).trigger('change');
                    } else {
                        cardBody.find('input[name="harga_modal_satuan[]"]').val('');
                        cardBody.find('select[name="satuan[]"]').val('').trigger('change');
                    }
                });
            }

            initBasicSelect2('#select-supplier', '-- Pilih atau ketik supplier baru --');

            $('#tambah-produk').on('click', function() {
                const container = $('#detail-produk-container');
                const produkOptionsData = @json($produk);
                const satuanOptionsData = @json($satuan);
                const kategoriOptionsData = @json($kategori);

                let selectProdukOptions = '<option></option>';
                produkOptionsData.forEach(p => {
                    selectProdukOptions += `<option value="${p.id}" data-modal="${p.modal_harga}" data-satuan-id="${p.satuan_id}">${p.nama_produk}</option>`;
                });

                let selectSatuanOptions = '<option></option>';
                satuanOptionsData.forEach(s => {
                    selectSatuanOptions += `<option value="${s.id}">${s.nama_satuan}</option>`;
                });

                let selectKategoriOptions = '<option value="">-- Pilih Kategori --</option>';
                kategoriOptionsData.forEach(k => {
                    selectKategoriOptions += `<option value="${k.id}">${k.nama_kategori}</option>`;
                });

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
                        <div class="row conditional-inputs" style="display: none;">
                            <div class="col-md-3 form-group mb-3">
                                <label>Kategori (untuk produk baru)</label>
                                <select name="new_kategori_id[]" class="form-control select2-basic-dynamic">${selectKategoriOptions}</select>
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label>Harga Modal</label>
                                <input type="number" name="harga_modal_satuan[]" class="form-control" placeholder="Harga Modal">
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label>Harga Jual</label>
                                <input type="number" name="harga_jual[]" class="form-control" placeholder="Harga Jual">
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label>Gambar Produk</label>
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
                initBasicSelect2(newCard.find('.select2-basic-dynamic'), '-- Pilih atau ketik kategori baru --');
            }).click(); // Otomatis tambahkan satu baris saat halaman dimuat

            $('#detail-produk-container').on('click', '.btn-remove', function() {
                $(this).closest('.produk-item').remove();
            });
        });
    </script>
@endpush
