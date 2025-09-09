<!DOCTYPE html>
<html lang="en">
 <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('template/dist') }}/assets/images/macan.jpg" type="image/x-icon">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ketersediaan Stok Produk</title>
    {{-- Menggunakan CSS dari template Mantis Anda untuk konsistensi --}}
    <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style.css') }}" id="main-style-link">
    <style>
        body {
            background-color: #f4f7fa;
        }

        .product-card {
            transition: transform .2s, box-shadow .2s;
            border-radius: 12px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, .15);
        }

        .product-img {
            height: 180px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .card-title {
            font-weight: 600;
        }

        .card-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1890ff;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="text-center mb-4">
            <h1 class="fw-bold">Ketersediaan Stok Produk</h1>
            <p class="text-muted">Cari produk yang Anda inginkan di bawah ini.</p>
        </div>

        {{-- Form Filter --}}
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <form action="{{ route('public.stok.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama produk..."
                            value="{{ request('search') }}">
                        <select name="kategori" class="form-select" style="max-width: 200px;">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Daftar Produk --}}
        <div class="row">
            @forelse($produk as $item)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card product-card h-100">
                        {{-- Ganti dengan gambar produk jika ada, jika tidak, gunakan placeholder --}}
                        <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://via.placeholder.com/300x200.png?text=' . urlencode($item->nama_produk) }}"
                            class="card-img-top product-img" alt="{{ $item->nama_produk }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1">{{ $item->nama_produk }}</h5>
                            <p class="text-muted small mb-2">{{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                            </p>
                            <h6 class="card-price mb-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</h6>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="text-muted">Ketersediaan:</span>

                                {{-- Logika untuk menampilkan jumlah stok dengan warna yang sesuai --}}
                                @if ($item->stok > 0)
                                    {{-- Jika stok ada, tampilkan jumlahnya dengan warna hijau --}}
                                    <span class="badge fs-6 bg-light-success text-success">
                                        {{ $item->stok }} {{ $item->satuan->nama_satuan ?? '' }}
                                    </span>
                                @else
                                    {{-- Jika stok habis, tampilkan tulisan "Habis" dengan warna merah --}}
                                    <span class="badge fs-6 bg-light-danger text-danger">
                                        Habis
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Produk tidak ditemukan. Silakan coba kata kunci atau filter lain.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="btn btn-secondary">Kembali ke Halaman Login</a>
        </div>
    </div>
</body>

</html>
