@extends('layouts.mantis') {{-- Sesuaikan dengan layout Anda --}}

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Detail Stok Masuk: {{ $stokMasuk->kode_stok_masuk }}</h4>
                <div>
                    <a class="btn btn-danger" href="{{ route('stok_masuk.index') }}">Kembali ke Daftar Stok Masuk</a>
                    {{-- Tombol Edit Stok Masuk Utama --}}
                    <a class="btn btn-warning" href="{{ route('stok_masuk.edit', $stokMasuk->id) }}">Edit Stok Masuk</a>
                </div>
            </div>
            <div class="card-body">
                {{-- Ringkasan Stok Masuk Utama --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Kode Stok Masuk:</strong> {{ $stokMasuk->kode_stok_masuk }}</p>
                        <p><strong>Tanggal Masuk:</strong>
                            {{ \Carbon\Carbon::parse($stokMasuk->tanggal_masuk)->format('d-m-Y') }}</p>
                        <p><strong>Supplier:</strong> {{ $stokMasuk->supplier->nama_supplier ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p><strong>Admin:</strong> {{ $stokMasuk->user->name ?? 'N/A' }}</p>
                        <p><strong>Deskripsi:</strong> {{ $stokMasuk->deskripsi ?? '-' }}</p>
                    </div>
                </div>

                <hr>

                <h5>Daftar Produk dalam Stok Masuk Ini</h5>
                <table class="table table-bordered" id="myTableDetail">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">No</th>
                            <th class="text-center text-nowrap">Produk</th>
                            <th class="text-center text-nowrap">Jumlah</th>
                            <th class="text-center text-nowrap">Satuan</th>
                            <th class="text-center text-nowrap">Kategori</th> {{-- Tambahkan kolom Kategori --}}

                            <th class="text-center text-nowrap">Tanggal Kadaluarsa</th>
                            <th class="text-center text-nowrap">Aksi</th> {{-- Aksi untuk detail individual jarang ada di halaman show --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokMasuk->detailStokMasuk as $detail)
                            <tr>
                                <td class="text-center text-nowrap">{{ $loop->iteration }}</td>
                                <td>{{ $detail->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                                <td class="text-center">{{ $detail->jumlah }}</td>
                                <td>{{ $detail->satuan?? '-' }}</td> {{-- Ini akan menampilkan nama satuan --}}
                                <td>{{ $detail->produk->kategori->nama_kategori ?? 'N/A' }}</td> {{-- Tampilkan nama kategori --}} <td
                                    class="text-center">
                                    @if ($detail->tanggal_kadaluarsa)
                                        {{ \Carbon\Carbon::parse($detail->tanggal_kadaluarsa)->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    <form action="{{ route('detail_stok_masuk.destroy', $detail->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-confirm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"class="text-center">Tidak ada detail produk untuk stok masuk ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- Jika Anda ingin DataTables di tabel detail ini juga, tambahkan script di bawah --}}
@push('scripts')
    <script>
        $(document).ready(function() {
            // SweetAlert confirmation for delete buttons
            $(document).on('click', '.delete-confirm', function(e) {
                e.preventDefault(); // Mencegah submit form default
                var form = $(this).closest('form'); // Dapatkan form terdekat

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Detail stok masuk ini akan dihapus dan stok produk akan dikembalikan!",
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

            // DataTables initialization
            // Hanya inisialisasi DataTables jika ada baris data selain header
            if ($('#myTableDetail tbody tr').length > 0 && $('#myTableDetail tbody tr td').attr('colspan') !== '7') { // Sesuaikan colspan
                $('#myTableDetail').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            }
        });
    </script>
@endpush