@extends('layout.app')

@section('content')
<div class="container mx-auto p-4">
    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="bg-white shadow-lg rounded-lg p-4 overflow-x-auto">
        <div class="text-lg font-semibold mb-4">Laporan Penjualan</div>
         
        <form method="GET" action="{{ route('laporan-penjualan.index') }}" class="grid grid-cols-4 gap-4 mb-2">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="w-full border px-3 py-2 rounded-md">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="w-full border px-3 py-2 rounded-md">
            </div>
            <div>
                <label for="kasir" class="block text-sm font-medium text-gray-700">Kasir</label>
                <select name="kasir" id="kasir" class="w-full border px-3 py-2 rounded-md">
                    <option value="">-- Semua Kasir --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                            {{ $user->username }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">
                    Terapkan Filter
                </button>
            </div>
            
        </form>
        
        <div class="flex justify-end mb-4">
            <a href="{{ route('laporan-penjualan.export', request()->all()) }}"
               class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                Export PDF
            </a>
        </div>

        <table class="min-w-full border text-xs">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-3 py-2 text-left">No</th>
                    <th class="border px-3 py-2 text-left">Tanggal</th>
                    <th class="border px-3 py-2 text-left">Username</th>
                    <th class="border px-3 py-2 text-left">Grand Total</th>
                    <th class="border px-3 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-3 py-2">{{ $transaksi->created_at->format('d-m-Y H:i') }}</td>
                        <td class="border px-3 py-2">{{ $transaksi->user->username }}</td>
                        <td class="border px-3 py-2">Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</td>
                        <td class="border px-3 py-2">
                            <button onclick="openModal({{ $transaksi->id }})"
                                class="text-blue-600 hover:underline">Lihat Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Tidak ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Detail --}}
    <div id="detailModal" class="fixed inset-0 bg-gray-700 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div id="modalBox" class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 ">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Detail Transaksi</h2>
                <button onclick="closeModal()" class="text-red-500 font-semibold text-sm">Tutup</button>
            </div>
            <div id="modalContent" class="text-sm text-gray-800">
                <p>Loading...</p>
            </div>
        </div>
    </div>
</div>


{{-- Script --}}
<script>
    function openModal(transaksiId) {
        document.getElementById('detailModal').classList.remove('hidden');
        fetch(`/transaksi/${transaksiId}`)
            .then(response => response.json())
            .then(data => {
                let content = `<p><strong>Username:</strong> ${data.user.username}</p>`;
                content += `<p><strong>Total:</strong> Rp ${new Intl.NumberFormat('id-ID').format(data.grand_total)}</p>`;
                content += `<h3 class='mt-4 font-semibold'>Detail Barang</h3><ul class="list-disc pl-5 mt-1">`;
                data.details.forEach(detail => {
                    content += `<li>${detail.barang.nama_barang} - ${detail.qty} x Rp ${new Intl.NumberFormat('id-ID').format(detail.subtotal)}</li>`;
                });
                content += `</ul>`;
                document.getElementById('modalContent').innerHTML = content;
            });
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    document.addEventListener("click", function (e) {
        const modal = document.getElementById("detailModal");
        const modalBox = document.getElementById("modalBox");

        if (!modal.classList.contains("hidden") && !modalBox.contains(e.target) && modal.contains(e.target)) {
            closeModal();
        }
    });
</script>
@endsection
