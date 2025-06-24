@extends('layout.app')

@section('title', 'Tambah Barang Masuk')

@section('content')
<div class="mt-4 mb-2 flex justify-end">
    <a href="{{ route('laporan-pembelian.index') }}" class="bg-green-500 text-white px-4 py-2 rounded-md text-sm">Lihat Laporan Pembelian</a>
</div>
<div class="bg-white p-6 rounded-xl shadow max-w-6xl mx-auto mt-6">
  <h2 class="text-2xl font-bold text-gray-800 mb-4">Tambah Barang Masuk</h2>

  <form action="{{ route('barang-masuk.store') }}" method="POST">
    @csrf

    {{-- Supplier --}}
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Supplier</label>
      <select name="supplier_id" class="w-full border rounded px-3 py-2">
        <option value="">-- Pilih Supplier --</option>
        @foreach($suppliers as $supplier)
          <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
        @endforeach
      </select>
    </div>

    {{-- Dynamic Barang Masuk Rows --}}
    <div id="barang-container">
      <div class="barang-row flex gap-4 mb-2">
        <select name="barang_id[]" class="border rounded px-2 py-1 w-1/3">
          <option value="">-- Pilih Barang --</option>
          @foreach($barangs as $barang)
            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
          @endforeach
        </select>

        <input type="number" name="jumlah[]" min="1" placeholder="Jumlah"
          class="border rounded px-2 py-1 w-1/4" />

        <input type="number" name="harga_satuan[]" min="0" placeholder="Harga Satuan"
          class="border rounded px-2 py-1 w-1/4" />

        <button type="button" class="btn-hapus-row text-red-600 font-bold">✖</button>
      </div>
    </div>

    {{-- Tombol Tambah --}}
    <div class="mb-4">
      <button type="button" id="tambah-barang"
        class="bg-green-500 text-white px-3 py-1 rounded shadow hover:bg-green-600">
        + Tambah Barang
      </button>
    </div>

    {{-- Simpan --}}
    <div class="flex justify-end">
      <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
        Simpan
      </button>
    </div>
  </form>
</div>

{{-- JS --}}
<script>
  document.getElementById('tambah-barang').addEventListener('click', function () {
    const container = document.getElementById('barang-container');
    const row = container.querySelector('.barang-row');
    const clone = row.cloneNode(true);

    // Reset nilai input
    clone.querySelectorAll('input').forEach(input => input.value = '');
    clone.querySelector('select').selectedIndex = 0;

    container.appendChild(clone);
  });

  // Delegasi event hapus
  document.getElementById('barang-container').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-hapus-row')) {
      const rows = this.querySelectorAll('.barang-row');
      if (rows.length > 1) {
        e.target.closest('.barang-row').remove();
      }
    }
  });
</script>
@endsection
