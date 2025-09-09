@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Form Transaksi</h4>
                <a class="btn btn-danger" href="{{ route('transaksi.index') }}">Kembali</a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form tidak berubah, hanya onsubmit dihapus karena event ditangani oleh jQuery --}}
                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf

                    {{-- Bagian Form Utama (tidak berubah) --}}
                    <div class="form-group my-2">
                        <label for="kode_transaksi">Kode Transaksi</label>
                        <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control"
                            value="{{ old('kode_transaksi', $kode_transaksi) }}" readonly>
                    </div>

                    <div class="form-group my-2">
                        <label for="user_id">Admin</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        @error('user_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="tanggal_transaksi">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                            value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
                        @error('tanggal_transaksi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ================================================================== --}}
                    {{-- >> BAGIAN DETAIL PRODUK YANG SUDAH DIPERBAIKI << --}}
                    {{-- ================================================================== --}}
                    <hr>
                    <h5 class="mb-3">Detail Produk</h5>

                    {{-- Wadah untuk semua kartu produk --}}
                    <div id="produk-container">
                        {{-- Data 'old' akan ditangani oleh JavaScript jika ada --}}
                    </div>

                    {{-- Tombol Tambah Produk --}}
                    <button type="button" id="tambah-produk" class="btn btn-secondary mb-3">Tambah Produk</button>

                    {{-- Tampilan Total Harga yang Lebih Jelas --}}
                    <div class="text-end mb-3">
                        <h4>Total Harga: <span id="total-harga-display" class="text-primary fw-bold">Rp 0</span></h4>
                    </div>
                    
                    {{-- Input Bayar dan Kembalian (tidak berubah) --}}
                    <div class="form-group my-2">
                        <label for="bayar">Bayar</label>
                        <input type="number" name="bayar" id="bayar" class="form-control" required min="0">
                    </div>

                    <div class="form-group my-2">
                        <label for="kembalian">Kembalian</label>
                        <input type="number" name="kembalian" id="kembalian" class="form-control" readonly>
                    </div>

                    {{-- Input hidden untuk total harga (tidak berubah) --}}
                    <input type="hidden" name="total_harga" id="total_harga_input">

                    <div class="my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        // Fungsi untuk memformat angka menjadi format Rupiah
        function formatRupiah(angka) {
            // Menggunakan Intl.NumberFormat untuk performa dan akurasi terbaik
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }

        // ===================================================================
        // >> FUNGSI TERPUSAT UNTUK MENGHITUNG SEMUA NILAI <<
        // ===================================================================
        function calculateTotals() {
            let totalHarga = 0;
            
            // Loop melalui setiap kartu produk yang ada
            $('.produk-item').each(function() {
                const card = $(this);
                const jumlah = parseFloat(card.find('input[name="jumlah[]"]').val()) || 0;
                const harga = parseFloat(card.find('input[name="harga_satuan[]"]').val()) || 0;
                const subtotal = jumlah * harga;
                
                // 1. Update input hidden subtotal (untuk dikirim ke backend jika perlu)
                card.find('input[name="subtotal[]"]').val(subtotal.toFixed(0));
                
                // 2. Update tampilan subtotal dengan format Rupiah
                card.find('.subtotal-display').val(formatRupiah(subtotal)); // <-- PERBAIKAN TAMPILAN
                
                totalHarga += subtotal;
            });

            // 3. Update tampilan Total Harga utama
            $('#total-harga-display').text(formatRupiah(totalHarga));
            
            // 4. Update input hidden total_harga untuk dikirim ke backend
            $('#total_harga_input').val(totalHarga.toFixed(0));

            // 5. Hitung dan update kembalian
            const bayar = parseFloat($('#bayar').val()) || 0;
            const kembalian = bayar - totalHarga;
            $('#kembalian').val(kembalian.toFixed(0));
        }

        // Fungsi untuk menambah baris produk baru (tidak berubah, tapi saya sertakan lagi)
        function addProductRow() {
            const container = $('#produk-container');
            const produkOptionsData = @json($produk);

            let selectProdukOptions = '<option value="">-- Pilih Produk --</option>';
            produkOptionsData.forEach(p => {
                if (p.stok > 0) {
                    selectProdukOptions += `<option value="${p.id}" data-harga="${p.harga}" data-stok="${p.stok}">${p.nama_produk} (Stok: ${p.stok})</option>`;
                }
            });

            const newCardHtml = `
                <div class="card produk-item mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-danger btn-remove">Hapus</button>
                        </div>
                        <div class="row">
                            <div class="col-md-8 form-group mb-3">
                                <label>Produk</label>
                                <select name="produk_id[]" class="form-control select2-transaksi" required>${selectProdukOptions}</select>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required min="1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Harga Satuan</label>
                                <input type="text" name="harga_satuan[]" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Subtotal</label>
                                <input type="text" class="form-control subtotal-display" value="Rp 0" readonly>
                                <input type="hidden" name="subtotal[]" value="0">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const newCard = $(newCardHtml);
            container.append(newCard);

            newCard.find('.select2-transaksi').select2({
                placeholder: '-- Pilih Produk --',
                theme: 'bootstrap-5'
            });
        }

        // --- EVENT LISTENERS ---

        // Klik tombol "Tambah Produk"
        $('#tambah-produk').on('click', addProductRow);

        // Event handler dinamis untuk perubahan pada elemen yang dibuat
        $('#produk-container').on('change', 'select[name="produk_id[]"]', function() {
            const selectedOption = $(this).find('option:selected');
            const harga = selectedOption.data('harga') || 0;
            const stok = selectedOption.data('stok') || 0;
            const card = $(this).closest('.produk-item');
            
            card.find('input[name="harga_satuan[]"]').val(harga);
            card.find('input[name="jumlah[]"]').attr('max', stok).val(1);
            
            calculateTotals(); // <-- Panggil fungsi terpusat
        });

        // Event handler untuk input jumlah dan bayar
        $('#produk-container').on('input', 'input[name="jumlah[]"]', calculateTotals); // <-- Panggil fungsi terpusat
        $('#bayar').on('input', calculateTotals); // <-- Panggil fungsi terpusat

        // Event handler untuk menghapus kartu
        $('#produk-container').on('click', '.btn-remove', function() {
            $(this).closest('.produk-item').remove();
            calculateTotals(); // <-- Panggil fungsi terpusat
        });

        // Tambahkan satu baris produk secara otomatis saat halaman dimuat
        addProductRow();
    });
</script>
@endpush
