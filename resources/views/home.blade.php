@extends('layouts.mantis')

@section('content')
    <!-- [ Metrik Dashboard Baru ] start -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Transaksi</h6>
                <h4 class="mb-3">
                    {{ number_format($totalTransaksiAllTime, 0, ',', '.') }}
                    <span class="badge bg-light-{{ $transaksiChange['color'] }} border border-{{ $transaksiChange['color'] }}">
                        <i class="ti {{ $transaksiChange['icon'] }}"></i>
                        {{ $transaksiChange['percentage'] }}%
                    </span>
                </h4>
                <p class="mb-0 text-muted text-sm">Jumlah transaksi utama yang tercatat (kumulatif)</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Stok Masuk </h6>
                <h4 class="mb-3">
                    {{ number_format($totalStokMasukAllTime, 0, ',', '.') }}
                    <span class="badge bg-light-{{ $stokMasukChange['color'] }} border border-{{ $stokMasukChange['color'] }}">
                        <i class="ti {{ $stokMasukChange['icon'] }}"></i>
                        {{ $stokMasukChange['percentage'] }}%
                    </span>
                </h4>
                <p class="mb-0 text-muted text-sm">Jumlah stok masuk utama yang tercatat (kumulatif)</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Profit</h6>
                <h4 class="mb-3">
                    Rp {{ number_format($totalProfitAllTime, 0, ',', '.') }}
                    <span class="badge bg-light-{{ $profitChange['color'] }} border border-{{ $profitChange['color'] }}">
                        <i class="ti {{ $profitChange['icon'] }}"></i> {{ $profitChange['percentage'] }}%
                    </span>
                </h4>
                <p class="mb-0 text-muted text-sm">Total keuntungan dari semua transaksi (kumulatif)</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Inventaris</h6>
                <h4 class="mb-3">
                    Rp {{ number_format($totalSalesAllTime, 0, ',', '.') }}
                    <span class="badge bg-light-{{ $salesChange['color'] }} border border-{{ $salesChange['color'] }}">
                        <i class="ti {{ $salesChange['icon'] }}"></i> {{ $salesChange['percentage'] }}%
                    </span>
                </h4>
                <p class="mb-0 text-muted text-sm">Total nilai Inventaris semua stok produk (kumulatif)</p>
            </div>
        </div>
    </div>
    <!-- [ Metrik Dashboard Baru ] end -->
{{-- ... PASTE KODE LENGKAP INI SEBAGAI PENGGANTINYA ... --}}
<div class="col-md-12 col-xl-8">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0">Grafik Penjualan</h5>
        {{-- Tombol untuk filter (bisa dikembangkan nanti) --}}
        <ul class="nav nav-pills justify-content-end mb-0" id="chart-tab-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="chart-tab-home-tab" data-bs-toggle="pill"
                    data-bs-target="#chart-tab-home" type="button" role="tab"
                    aria-controls="chart-tab-home" aria-selected="false">Bulanan</button>
            </li>
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link active" id="chart-tab-profile-tab" data-bs-toggle="pill"
                    data-bs-target="#chart-tab-profile" type="button" role="tab"
                    aria-controls="chart-tab-profile" aria-selected="true">Mingguan</button>
            </li> --}}
        </ul>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="chart-tab-tabContent">
                {{-- Wadah untuk Grafik Bulanan --}}
                <div class="tab-pane fade" id="chart-tab-home" role="tabpanel" aria-labelledby="chart-tab-home-tab" tabindex="0">
                    <div id="visitor-chart-1" style="height: 350px;"></div>
                </div>
                {{-- Wadah untuk Grafik Mingguan (sementara menampilkan data bulanan) --}}
                <div class="tab-pane fade show active" id="chart-tab-profile" role="tabpanel" aria-labelledby="chart-tab-profile-tab" tabindex="0">
                    <div id="visitor-chart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 col-xl-4">
    <div class="card">
        <div class="card-body">
            <h6 class="mb-2 f-w-400 text-muted">Statistik Pendapatan Bulan Ini</h6>
            {{-- Tampilkan total profit bulan ini dari controller --}}
            <h3 class="mb-3">Rp {{ number_format($profitCurrentMonthSum, 0, ',', '.') }}</h3>
            
            {{-- Wadah untuk grafik (sudah benar) --}}
            <div id="income-overview-chart" style="height: 300px;"></div>
        </div>
    </div>
</div>

{{-- 
                <div class="col-md-12 col-xl-8">
                    <h5 class="mb-3">Recent Orders</h5>
                    <div class="card tbl-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead>
                                        <tr>
                                            <th>TRACKING NO.</th>
                                            <th>PRODUCT NAME</th>
                                            <th>TOTAL ORDER</th>
                                            <th>STATUS</th>
                                            <th class="text-end">TOTAL AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="#" class="text-muted">84564564</a></td>
                                            <td>Camera Lens</td>
                                            <td>40</td>
                                            <td><span class="d-flex align-items-center gap-2"><i
                                                        class="fas fa-circle text-danger f-10 m-r-5"></i>Rejected</span>
                                            </td>
                                            <td class="text-end">$40,570</td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="text-muted">84564564</a></td>
                                            <td>Laptop</td>
                                            <td>300</td>
                                            <td><span class="d-flex align-items-center gap-2"><i
                                                        class="fas fa-circle text-warning f-10 m-r-5"></i>Pending</span>
                                            </td>
                                            <td class="text-end">$180,139</td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="text-muted">84564564</a></td>
                                            <td>Mobile</td>
                                            <td>355</td>
                                            <td><span class="d-flex align-items-center gap-2"><i
                                                        class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span>
                                            </td>
                                            <td class="text-end">$180,139</td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="text-muted">84564564</a></td>
                                            <td>Camera Lens</td>
                                            <td>40</td>
                                            <td><span class="d-flex align-items-center gap-2"><i
                                                        class="fas fa-circle text-danger f-10 m-r-5"></i>Rejected</span>
                                            </td>
                                            <td class="text-end">$40,570</td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="text-muted">84564564</a></td>
                                            <td>Laptop</td>
                                            <td>300</td>
                                            <td><span class="d-flex align-items-center gap-2"><i
                                                        class="fas fa-circle text-warning f-10 m-r-5"></i>Pending</span>
                                            </td>
                                            <td class="text-end">$180,139</td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="text-muted">84564564</a></td>
                                            <td>Mobile</td>
                                            <td>355</td>
                                            <td><span class="d-flex align-items-center gap-2"><i
                                                        class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span>
                                            </td>
                                            <td class="text-end">$180,139</td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="text-muted">84564564</a></td>
                                            <td>Camera Lens</td>
                                            <td>40</td>
                                            <td><span class="d-flex align-items-center gap-2"><i
                                                        class="fas fa-circle text-danger f-10 m-r-5"></i>Rejected</span>
                                            </td>
                                            <td class="text-end">$40,570</td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="text-muted">84564564</a></td>
                                            <td>Laptop</td>
                                            <td>300</td>
                                            <td><span class="d-flex align-items-center gap-2"><i
                                                        class="fas fa-circle text-warning f-10 m-r-5"></i>Pending</span>
                                            </td>
                                            <td class="text-end">$180,139</td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="text-muted">84564564</a></td>
                                            <td>Mobile</td>
                                            <td>355</td>
                                            <td><span class="d-flex align-items-center gap-2"><i
                                                        class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span>
                                            </td>
                                            <td class="text-end">$180,139</td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="text-muted">84564564</a></td>
                                            <td>Mobile</td>
                                            <td>355</td>
                                            <td><span class="d-flex align-items-center gap-2"><i
                                                        class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span>
                                            </td>
                                            <td class="text-end">$180,139</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-4">
                    <h5 class="mb-3">Analytics Report</h5>
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <a href="#"
                                class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Company
                                Finance Growth<span class="h5 mb-0">+45.14%</span></a>
                            <a href="#"
                                class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Company
                                Expenses Ratio<span class="h5 mb-0">0.58%</span></a>
                            <a href="#"
                                class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Business
                                Risk Cases<span class="h5 mb-0">Low</span></a>
                        </div>
                        <div class="card-body px-2">
                            <div id="analytics-report-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-xl-8">
                    <h5 class="mb-3">Sales Report</h5>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 f-w-400 text-muted">This Week Statistics</h6>
                            <h3 class="mb-0">$7,650</h3>
                            <div id="sales-report-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-4">
                    <h5 class="mb-3">Transaction History</h5>
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s rounded-circle text-success bg-light-success">
                                            <i class="ti ti-gift f-18"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Order #002434</h6>
                                        <p class="mb-0 text-muted">Today, 2:00 AM</P>
                                    </div>
                                    <div class="flex-shrink-0 text-end">
                                        <h6 class="mb-1">+ $1,430</h6>
                                        <p class="mb-0 text-muted">78%</P>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s rounded-circle text-primary bg-light-primary">
                                            <i class="ti ti-message-circle f-18"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Order #984947</h6>
                                        <p class="mb-0 text-muted">5 August, 1:45 PM</P>
                                    </div>
                                    <div class="flex-shrink-0 text-end">
                                        <h6 class="mb-1">- $302</h6>
                                        <p class="mb-0 text-muted">8%</P>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s rounded-circle text-danger bg-light-danger">
                                            <i class="ti ti-settings f-18"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Order #988784</h6>
                                        <p class="mb-0 text-muted">7 hours ago</P>
                                    </div>
                                    <div class="flex-shrink-0 text-end">
                                        <h6 class="mb-1">- $682</h6>
                                        <p class="mb-0 text-muted">16%</P>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    ... (Sisa konten spesifik halaman home Anda seperti grafik, tabel, dll.) ... --}}

@endsection

@push('scripts')
    {{-- Skrip ini spesifik untuk halaman home, jadi biarkan di sini --}}
    <script>
        $(document).ready(function() {
            // Cek apakah notifikasi harus ditampilkan
            @if ($showExpiringNotification && $expiringProductsForNotification->isNotEmpty())
                let expiringProductsHtml =
                    '<ul style="text-align: left; list-style-type: disc; padding-left: 20px;">';
                @foreach ($expiringProductsForNotification as $detail)
                    const diffInDays = {{ \Carbon\Carbon::now()->diffInDays($detail->tanggal_kadaluarsa, false) }};
                    let statusBadge = '';
                    if (diffInDays < 0) {
                        statusBadge = '<span class="badge bg-danger">Kadaluarsa (' + Math.abs(diffInDays) +
                            ' hari lalu)</span>';
                    } else if (diffInDays <= 30) {
                        statusBadge = '<span class="badge bg-warning">' + diffInDays + ' hari lagi</span>';
                    } else {
                        statusBadge = '<span class="badge bg-info">' + diffInDays + ' hari lagi</span>';
                    }

                    expiringProductsHtml +=
                        '<li><strong>{{ $detail->stokMasuk->kode_stok_masuk ?? 'N/A' }} - {{ $detail->produk->nama_produk ?? 'N/A' }}</strong> (Stok: {{ $detail->jumlah }} {{ $detail->satuan }}) - Kadaluarsa: {{ \Carbon\Carbon::parse($detail->tanggal_kadaluarsa)->format('d-m-Y') }} ' +
                        statusBadge + '</li>';
                @endforeach
                expiringProductsHtml += '</ul>';

                Swal.fire({
                    title: 'Produk Akan Kadaluarsa!',
                    html: '<p>Berikut adalah daftar produk yang akan kadaluarsa dalam 3 bulan ke depan:</p>' +
                        expiringProductsHtml,
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'Oke',
                });
            @endif
        });
    </script>
@endpush

@push('scripts')
<script>
    // Gunakan $(document).ready untuk memastikan semua elemen HTML sudah dimuat
    $(document).ready(function() {
        // Fungsi untuk mengambil data dari server dan menginisialisasi grafik
        function loadDashboardCharts() {
            $.ajax({
                url: "{{ route('dashboard.chart.data') }}", // Panggil rute yang sudah dibuat
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Panggil fungsi untuk render setiap grafik dengan data yang diterima
                    renderSalesChart(data.labels, data.penjualan);
                    renderIncomeChart(data.labels, data.pendapatan);
                },
                error: function(error) {
                    console.error("Gagal memuat data grafik:", error);
                }
            });
        }

        // Fungsi untuk me-render Grafik Penjualan (Stok Masuk vs Transaksi)
        function renderSalesChart(labels, salesData) {
            var options = {
                chart: { height: 350, type: 'area', toolbar: { show: false } },
                colors: ['#1890ff', '#13c2c2'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                series: [
                    { name: 'Stok Masuk', data: salesData.stok_masuk },
                    { name: 'Transaksi', data: salesData.transaksi }
                ],
                xaxis: { categories: labels }
            };

            // Hancurkan grafik lama jika ada, lalu render yang baru
            $('#visitor-chart-1').empty();
            var chart = new ApexCharts(document.querySelector('#visitor-chart-1'), options);
            chart.render();
            
            // Lakukan hal yang sama untuk grafik mingguan (untuk sementara pakai data bulanan)
            $('#visitor-chart').empty();
            var chart2 = new ApexCharts(document.querySelector('#visitor-chart'), options);
            chart2.render();
        }

        // Fungsi untuk me-render Grafik Pendapatan
        function renderIncomeChart(labels, incomeData) {
            var options = {
                chart: { type: 'bar', height: 300, toolbar: { show: false } },
                colors: ['#13c2c2'],
                plotOptions: { bar: { columnWidth: '45%', borderRadius: 4 } },
                dataLabels: { enabled: false },
                series: [{ name: 'Pendapatan', data: incomeData.total }],
                xaxis: {
                    categories: labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                      tickAmount: 6, // Tampilkan sekitar 6 label (misal: Jan, Mar, May, Jul, Sep, Nov)
            labels: {
                rotate: 0 // Pastikan tetap horizontal
            }
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                        }
                    }
                },
                grid: { show: false }
            };
            
            $('#income-overview-chart').empty();
            var chart = new ApexCharts(document.querySelector('#income-overview-chart'), options);
            chart.render();
        }

        // Panggil fungsi utama untuk memuat data saat halaman siap
        loadDashboardCharts();
    });
</script>
@endpush
