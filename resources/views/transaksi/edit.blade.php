@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Edit Transaksi</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('transaksi.index') }}">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                {{-- Tampilkan error validasi jika ada --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" onsubmit="updateTotalHarga()">
                    @csrf
                    @method('PUT') {{-- Penting untuk metode UPDATE --}}

                    {{-- Ganti blok Kode Transaksi Anda dengan ini --}}
                    <div class="form-group my-2">
                        <label for="kode_transaksi_display">Kode Transaksi</label>
                        {{-- Input ini hanya untuk tampilan, tidak mengirimkan data --}}
                        <input type="text" id="kode_transaksi_display" class="form-control"
                            value="{{ $transaksi->kode_transaksi }}" readonly>

                        {{-- ========================================================== --}}
                        {{-- >> TAMBAHKAN INPUT TERSEMBUNYI INI UNTUK MENGIRIM DATA << --}}
                        {{-- ========================================================== --}}
                        <input type="hidden" name="kode_transaksi" value="{{ $transaksi->kode_transaksi }}">
                    </div>

                    <div class="form-group my-2">
                        <label for="user_id">Admin</label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Admin --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user_id', $transaksi->user_id) == $user->id ? 'selected' : '' }}>
                                    {{-- FIX: Changed $item->id to $user->id --}}
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="tanggal_transaksi">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" id="tanggal_transaksi"
                            class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                            value="{{ old('tanggal_transaksi', \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d')) }}"
                            required>
                        @error('tanggal_transaksi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Tambahkan field deskripsi --}}
                    <div class="form-group my-2">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $transaksi->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <hr>
                    <h5>Detail Produk</h5>
                    <div id="produk-wrapper">
                        {{-- Loop untuk menampilkan detail transaksi yang sudah ada --}}
                        @forelse($transaksi->detailTransaksi as $index => $detail)
                            <div class="row produk-item mb-2">
                                <div class="col-md-4">
                                    <select name="produk_id[]"
                                        class="form-control @error('produk_id.' . $index) is-invalid @enderror"
                                        onchange="updateHarga(this)" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($produk as $item)
                                            <option value="{{ $item->id }}" data-harga="{{ $item->harga }}"
                                                {{ old('produk_id.' . $index, $detail->produk_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('produk_id.' . $index)
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="jumlah[]"
                                        class="form-control @error('jumlah.' . $index) is-invalid @enderror"
                                        value="{{ old('jumlah.' . $index, $detail->jumlah) }}"
                                        oninput="hitungSubtotal(this)" placeholder="Jumlah" required min="1">
                                    @error('jumlah.' . $index)
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="harga_satuan[]"
                                        class="form-control @error('harga_satuan.' . $index) is-invalid @enderror"
                                        value="{{ old('harga_satuan.' . $index, $detail->harga_satuan) }}" readonly>
                                    @error('harga_satuan.' . $index)
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="subtotal[]" class="form-control subtotal-field"
                                        value="{{ old('subtotal.' . $index, $detail->subtotal) }}" readonly>
                                    @error('subtotal.' . $index)
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                                </div>
                            </div>
                        @empty
                            {{-- Jika tidak ada detail transaksi, tampilkan satu baris kosong --}}
                            <div class="row produk-item mb-2">
                                <div class="col-md-4">
                                    <select name="produk_id[]" class="form-control" onchange="updateHarga(this)" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($produk as $item)
                                            <option value="{{ $item->id }}" data-harga="{{ $item->harga }}">
                                                {{ $item->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah"
                                        oninput="hitungSubtotal(this)" required min="1">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="harga_satuan[]" class="form-control" readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="subtotal[]" class="form-control subtotal-field" readonly>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <button type="button" id="tambah-produk" class="btn btn-secondary mb-3">Tambah Produk</button>

                    <div class="form-group my-2">
                        <label for="bayar">Bayar</label>
                        <input type="number" name="bayar" id="bayar"
                            class="form-control @error('bayar') is-invalid @enderror"
                            value="{{ old('bayar', $transaksi->bayar) }}" oninput="hitungKembalian()" required
                            min="0">
                        @error('bayar')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="kembalian">Kembalian</label> {{-- Label untuk Kembalian --}}
                        <input type="number" name="kembalian" id="kembalian" class="form-control"
                            value="{{ old('kembalian', $transaksi->kembalian) }}" readonly> {{-- Menampilkan Kembalian --}}
                    </div>

                    {{-- Input profit dihapus karena dihitung di backend --}}
                    {{-- <div class="form-group my-2">
                        <label for="profit">Profit</label>
                        <input type="number" name="profit" id="profit" class="form-control"
                            value="{{ old('profit', $transaksi->profit) }}" readonly>
                    </div> --}}

                    <input type="hidden" name="total_harga" id="total_harga_input"
                        value="{{ old('total_harga', $transaksi->total_harga) }}">

                    <div class="my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk mengupdate harga satuan berdasarkan produk yang dipilih
        function updateHarga(selectElement) {
            const harga = selectElement.selectedOptions[0].getAttribute("data-harga") || 0;
            const row = selectElement.closest(".produk-item");
            row.querySelector("input[name=\"harga_satuan[]\"]").value = harga;
            hitungSubtotal(row.querySelector("input[name=\"jumlah[]\"]"));
        }

        // Fungsi untuk menghitung subtotal per baris produk
        function hitungSubtotal(jumlahInput) {
            const row = jumlahInput.closest(".produk-item");
            const jumlah = parseFloat(jumlahInput.value) || 0;
            const harga = parseFloat(row.querySelector("input[name=\"harga_satuan[]\"]").value) || 0;
            const subtotal = (jumlah * harga).toFixed(2); // Format 2 desimal
            row.querySelector("input[name=\"subtotal[]\"]").value = subtotal;
            updateTotalHarga();
        }

        // Fungsi untuk menghitung total harga keseluruhan dari semua subtotal
        function updateTotalHarga() {
            const subtotalFields = document.querySelectorAll("input[name=\"subtotal[]\"]");
            let total = 0;
            subtotalFields.forEach(field => {
                total += parseFloat(field.value) || 0;
            });
            const totalFormatted = total.toFixed(2); // Format 2 desimal
            document.getElementById("total_harga_input").value = totalFormatted;
            hitungKembalian(); // Panggil hitungKembalian setelah total harga diperbarui
        }

        // Fungsi untuk menghitung kembalian
        function hitungKembalian() {
            const total = parseFloat(document.getElementById("total_harga_input").value) || 0;
            const bayar = parseFloat(document.getElementById("bayar").value) || 0;
            document.getElementById("kembalian").value = (bayar - total).toFixed(2); // Format 2 desimal
        }

        // Event listener untuk tombol "Tambah Produk"
        document.getElementById("tambah-produk").addEventListener("click", function() {
            const wrapper = document.getElementById("produk-wrapper");
            const produkOptions = @json($produk); // Pastikan $produk tersedia dari controller
            let selectOptions = "<option value=\"\">-- Pilih Produk --</option>";
            produkOptions.forEach(p => {
                selectOptions +=
                    `<option value="${p.id}" data-harga="${p.harga}">${p.nama_produk}</option>`;
            });

            const item = document.createElement("div");
            item.className = "row produk-item mb-2";
            item.innerHTML = `
                <div class="col-md-4">
                    <select name="produk_id[]" class="form-control" onchange="updateHarga(this)" required>
                        ${selectOptions}
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" oninput="hitungSubtotal(this)" required min="1">
                </div>
                <div class="col-md-2">
                    <input type="number" name="harga_satuan[]" class="form-control" readonly>
                </div>
                <div class="col-md-2">
                    <input type="number" name="subtotal[]" class="form-control subtotal-field" readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                </div>
            `;
            wrapper.appendChild(item);
            updateTotalHarga(); // Panggil updateTotalHarga setelah menambah produk baru
        });

        // Event listener untuk tombol "Hapus" pada produk item
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("btn-remove")) {
                e.target.closest(".produk-item").remove();
                updateTotalHarga(); // Panggil updateTotalHarga setelah menghapus produk
            }
        });

        // Hitung total awal saat halaman dimuat
        document.addEventListener("DOMContentLoaded", () => {
            updateTotalHarga();
        });
    </script>
@endsection
