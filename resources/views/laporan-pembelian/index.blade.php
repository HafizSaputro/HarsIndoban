@extends('layout.app')

@section('title', 'Laporan Barang Masuk')

@section('content')
<div class="bg-white p-6 rounded-xl shadow max-w-6xl mx-auto mt-6">
  <h2 class="text-2xl font-bold text-gray-800 mb-4">Laporan Barang Masuk</h2>

  {{-- Filter Form --}}
  <form method="GET" action="{{ route('laporan-pembelian.index') }}" class="grid md:grid-cols-4 gap-4 mb-6">
    <div>
      <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
      <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
        class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2">
    </div>
    <div>
      <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
      <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
        class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2">
    </div>
    <div>
      <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
      <select id="supplier_id" name="supplier_id"
        class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2">
        <option value="">-- Semua Supplier --</option>
        @foreach($suppliers as $supplier)
          <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
            {{ $supplier->nama_supplier }}
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
    <a href="{{ route('laporan-pembelian.export', request()->all()) }}"
       class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
        Export PDF
    </a>
  </div>


  {{-- Tabel Laporan --}}
  <div class="overflow-x-auto">
    <table class="w-full border border-gray-200 text-sm">
      <thead class="bg-gray-100">
        <tr class="text-left">
          <th class="px-4 py-2 border">#</th>
          <th class="px-4 py-2 border">Tanggal</th>
          <th class="px-4 py-2 border">Nama Barang</th>
          <th class="px-4 py-2 border">Supplier</th>
          <th class="px-4 py-2 border text-right">Jumlah</th>
          <th class="px-4 py-2 border text-right">Harga Satuan</th>
          <th class="px-4 py-2 border text-right">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @forelse($laporan as $item)
        <tr>
          <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
          <td class="px-4 py-2 border">{{ $item->created_at->format('d-m-Y') }}</td>
          <td class="px-4 py-2 border">{{ $item->barang->nama_barang }}</td>
          <td class="px-4 py-2 border">{{ $item->supplier->nama_supplier }}</td>
          <td class="px-4 py-2 border text-right">{{ $item->jumlah }}</td>
          <td class="px-4 py-2 border text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
          <td class="px-4 py-2 border text-right">Rp {{ number_format($item->jumlah * $item->harga_satuan, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
