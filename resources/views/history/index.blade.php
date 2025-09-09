@extends('layouts.mantis')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Riwayat</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr>
                            <th class="text-center text-nowrap">No </th>
                            <th class="text-center text-nowrap">Nama User</th>
                            <th class="text-center text-nowrap">Tanggal</th>
                            <th class="text-center text-nowrap">Aktivitas</th>
                            <th class="text-center text-nowrap">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $index => $item)
                            <tr>
                                <td class="text-center text-nowrap">{{ $index + 1 }}</td>
                                <td class="text-nowrap">{{ $item->nama }}</td>
                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td class="text-center text-nowrap">{{ $item->aktifitas ?? '-' }}</td>
                                <td class="text-nowrap">
                                    {{-- ========================================================== --}}
                                    {{-- >> LOGIKA LINK DINAMIS DITERAPKAN DI SINI << --}}
                                    {{-- ========================================================== --}}
                                    @if (Str::contains($item->aktifitas, 'Stok Masuk') && $item->stokMasuk)
                                        <a href="{{ route('stok_masuk.show', $item->stokMasuk->id) }}" class="fw-bold">
                                            {{ $item->detail }}
                                        </a>
                                    @elseif (Str::contains($item->aktifitas, 'Transaksi') && $item->transaksi)
                                        <a href="{{ route('transaksi.show', $item->transaksi->id) }}" class="fw-bold">
                                            {{ $item->detail }}
                                        </a>
                                    @elseif (Str::contains($item->aktifitas, 'produk') && $item->produk)
                                        <a href="{{ route('produk.show', $item->produk->id) }}" class="fw-bold">
                                            {{ $item->detail }}
                                        </a>
                                    @else
                                        {{-- Tampilkan sebagai teks biasa jika tidak ada link yang cocok --}}
                                        {{ $item->detail }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada riwayat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
        @if ($history->isNotEmpty())
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable();
                });
            </script>
        @endif
    @endpush
@endsection
