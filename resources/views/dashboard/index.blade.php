@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-xl font-bold mb-4">Dashboard Penjualan</h1>

    <!-- Filter Tanggal -->
    <form method="GET" class="mb-6 flex flex-wrap items-center gap-4">
        <div>
            <label for="start_date" class="mr-2 font-semibold">Dari:</label>
            <input type="date" name="start_date" id="start_date"
                value="{{ request('start_date', now()->subDays(31 )->toDateString()) }}"
                class="border rounded px-3 py-1">
        </div>
        <div>
            <label for="end_date" class="mr-2 font-semibold">Sampai:</label>
            <input type="date" name="end_date" id="end_date"
                value="{{ request('end_date', now()->toDateString()) }}"
                class="border rounded px-3 py-1">
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Terapkan</button>
        </div>
    </form>




    <!-- Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <p class="text-sm text-gray-500">Total Penjualan</p>
            <h2 class="text-xl font-bold text-green-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h2>
        </div>

        <div class="bg-white shadow rounded-lg p-4 text-center">
            <p class="text-sm text-gray-500">Total Pengeluaran</p>
            <h2 class="text-xl font-bold text-purple-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
        </div>

        <div class="bg-white shadow rounded-lg p-4 text-center">
            <p class="text-sm text-gray-500">Total Pemasukan</p>
            <h2 class="text-xl font-bold text-purple-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h2>
        </div>

        <div class="bg-white shadow rounded-lg p-4 text-center">
            <p class="text-sm text-gray-500">Jumlah Transaksi</p>
            <h2 class="text-xl font-bold text-blue-600">{{ $jumlahTransaksi }}</h2>
        </div>

        <div class="bg-white shadow rounded-lg p-4 text-center">
            <p class="text-sm text-gray-500">Barang Terlaris</p>
            @if($barangTerlaris)
                <h3 class="text-sm font-semibold">{{ $barangTerlaris->barang->nama_barang }}</h3>
                <p class="text-sm text-gray-500">Terjual: {{ $barangTerlaris->total_qty }} pcs</p>
            @else
                <p class="text-gray-400 text-sm">Tidak ada data</p>
            @endif
        </div>
    </div>


    <!-- Filter Tampilan Grafik -->
    <div class="mb-4">
        <input type="hidden" id="start_date_grafik" value="{{ request('start_date', now()->subDays(30)->toDateString()) }}">
        <input type="hidden" id="end_date_grafik" value="{{ request('end_date', now()->toDateString()) }}">

        <label class="font-semibold mr-2">Tampilan Grafik:</label>
        <select id="mode" name="mode" class="form-select">
            <option value="harian">Harian</option>
            <option value="mingguan">Mingguan</option>
            <option value="bulanan">Bulanan</option>
            <option value="tahunan">Tahunan</option>
        </select>        
    </div>

    <!-- Grafik -->
    <div class="grid grid-cols-1 gap-4 mb-6">
        <!-- Grafik Penjualan -->
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-md font-semibold mb-2">Grafik Penjualan</h2>
            <canvas id="grafikPenjualan" height="100"></canvas>
        </div>

        <!-- Grafik Pembelian -->
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-md font-semibold mb-2">Grafik Pembelian</h2>
            <canvas id="grafikPembelian" height="100"></canvas>
        </div>

        <!-- Grafik Barang Terlaris -->
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-md font-semibold mb-2">5 Barang Terlaris</h2>
            <canvas id="topProductsChart" height="100"></canvas>
        </div>
    </div>

</div>




<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function updateModeOptions() {
    const start = new Date(document.getElementById('start_date').value);
    const end = new Date(document.getElementById('end_date').value);

    if (isNaN(start) || isNaN(end)) return;

    const msInDay = 1000 * 60 * 60 * 24;
    const diffInDays = Math.floor((end - start) / msInDay);
    const diffInMonths = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start.getMonth());
    const diffInYears = end.getFullYear() - start.getFullYear();

    const modeSelect = document.getElementById('mode');

    // Simpan semua opsi default
    const allModes = [
        { value: 'harian', label: 'Harian' },
        { value: 'mingguan', label: 'Mingguan' },
        { value: 'bulanan', label: 'Bulanan' },
        { value: 'tahunan', label: 'Tahunan' }
    ];

    // Filter mode yang valid berdasarkan range tanggal
    const validModes = allModes.filter(mode => {
        if (mode.value === 'harian') {
            return diffInDays <= 31;
        }
        if (mode.value === 'mingguan') {
            return diffInDays >= 14 && diffInMonths <= 3;
        }
        if (mode.value === 'bulanan') {
            return diffInMonths >= 1 && diffInYears <= 5;
        }
        if (mode.value === 'tahunan') {
            return diffInYears >= 1 && diffInYears <= 10;
        }
        return true;
    });

    // Simpan mode yang sedang dipilih sekarang
    const currentValue = modeSelect.value;

    // Kosongkan dan rebuild opsi <select>
    modeSelect.innerHTML = '';
    validModes.forEach(mode => {
        const opt = document.createElement('option');
        opt.value = mode.value;
        opt.textContent = mode.label;
        modeSelect.appendChild(opt);
    });

    // Jika opsi sebelumnya masih valid, pilihkan lagi
    const stillValid = validModes.find(m => m.value === currentValue);
    if (stillValid) {
        modeSelect.value = currentValue;
    } else {
        modeSelect.value = validModes[0]?.value || '';
        muatDataGrafik(); // hanya refresh grafik jika terjadi perubahan nilai
    }
}


// Jalankan saat tanggal diubah
document.getElementById('start_date').addEventListener('change', updateModeOptions);
document.getElementById('end_date').addEventListener('change', updateModeOptions);


    const penjualanCtx = document.getElementById('grafikPenjualan').getContext('2d');
    const pembelianCtx = document.getElementById('grafikPembelian').getContext('2d');
    let grafikPenjualan;
    let grafikPembelian;

    const buatGrafik = (ctx, label, data, warna) => {
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: label,
                    data: data.values,
                    borderColor: warna,
                    backgroundColor: warna.replace('1)', '0.2)'),
                    tension: 0.4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: context => `${context.dataset.label}: Rp ${new Intl.NumberFormat('id-ID').format(context.parsed.y)}`
                        }
                    }
                }
            }
        });
    };

    const muatDataGrafik = () => {
        const mode = document.getElementById('mode').value;
        const start = document.getElementById('start_date_grafik').value;
        const end = document.getElementById('end_date_grafik').value;

        fetch(`/dashboard/grafik-data?start_date=${start}&end_date=${end}&mode=${mode}`)
            .then(res => res.json())
            .then(data => {
                // Hancurkan grafik lama jika ada
                if (grafikPenjualan) grafikPenjualan.destroy();
                if (grafikPembelian) grafikPembelian.destroy();

                // Buat grafik baru
                grafikPenjualan = buatGrafik(penjualanCtx, 'Penjualan', {
                    labels: data.labels,
                    values: data.penjualanData
                }, 'rgba(75, 192, 192, 1)');

                grafikPembelian = buatGrafik(pembelianCtx, 'Pembelian', {
                    labels: data.labels,
                    values: data.pembelianData
                }, 'rgba(255, 99, 132, 1)');
            });
    };

    document.getElementById('mode').addEventListener('change', muatDataGrafik);

    document.addEventListener('DOMContentLoaded', () => {
        muatDataGrafik(); // initial load
    });

    // Grafik Barang Terlaris (tetap)
    const topProductsCtx = document.getElementById('topProductsChart');
    new Chart(topProductsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topBarang->pluck('barang.nama_barang')) !!},
            datasets: [{
                label: 'Jumlah Terjual',
                data: {!! json_encode($topBarang->pluck('total_qty')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

@endsection
