@extends('layouts.mantis') {{-- Sesuaikan dengan layout Anda --}}

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Detail Transaksi: {{ $transaksi->kode_transaksi }}</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('transaksi.index') }}">Kembali ke Daftar Transaksi</a>
                    {{-- Tombol Edit Transaksi Utama --}}
                    <a class="btn btn-warning" href="{{ route('transaksi.edit', $transaksi->id) }}">Edit Transaksi</a>
                </div>
            </div>
            <div class="card-body">
                {{-- Ringkasan Transaksi Utama --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Kode Transaksi:</strong> {{ $transaksi->kode_transaksi }}</p>
                        <p><strong>Admin:</strong> {{ $transaksi->user->name ?? '-' }}</p>
                        <p><strong>Tanggal Transaksi:</strong>
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p><strong>Deskripsi:</strong> {{ $transaksi->deskripsi ?? '-' }}</p>
                        <p><strong>Total Harga:</strong> Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
                        <p><strong>Bayar:</strong> Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</p>
                        <p><strong>Profit:</strong> Rp {{ number_format($transaksi->profit, 0, ',', '.') }}</p>
                        <p><strong>Kembalian:</strong>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</p>

                    </div>
                </div>

                <hr>

                <h5>Daftar Produk dalam Transaksi Ini</h5>
                <table class="table table-bordered" id="myTableDetail">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">No</th>
                            <th class="text-center text-nowrap">Produk</th>
                            <th class="text-center text-nowrap">Jumlah</th>
                            <th class="text-center text-nowrap">Harga Satuan</th>
                            <th class="text-center text-nowrap">Subtotal Item</th>
                            <th class="text-center text-nowrap">Kembalian</th>

                            <th class="text-center text-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi->detailTransaksi as $detail)
                            <tr>
                                <td class="text-center text-nowrap">{{ $loop->iteration }}</td>
                                <td>{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>

                                <td class="text-center text-nowrap">
                                    {{-- Tombol Hapus Detail Individual --}}
                                    <form action="{{ route('detail_transaksi.destroy', $detail->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-confirm">Hapus</button>
                                    </form>
                                    {{-- Tombol Edit Detail tidak diperlukan di sini karena edit dilakukan di form transaksi.edit --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada detail produk untuk transaksi ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // SweetAlert confirmation for delete buttons
            // Kode ini akan mencari SEMUA elemen dengan kelas .delete-confirm di halaman ini
            $(document).on('click', '.delete-confirm', function(e) {
                e.preventDefault(); // Mencegah submit form default
                var form = $(this).closest('form'); // Dapatkan form terdekat

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    // Sesuaikan pesan agar relevan dengan konteks transaksi
                    text: "Detail transaksi ini akan dihapus dan stok produk akan dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit form jika dikonfirmasi
                    }
                });
            });

            // Opsional: Inisialisasi DataTables jika Anda menggunakannya di halaman ini
            if ($('#myTableDetail tbody tr').length > 0 && !$('#myTableDetail tbody tr td').first().attr('colspan')) {
                $('#myTableDetail').DataTable();
            }
        });
    </script>
@endpush