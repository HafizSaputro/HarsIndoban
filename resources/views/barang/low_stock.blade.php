@extends('layout.app')

@section('title', 'Barang Stok Rendah')

@section('content')
<div class="container mx-auto mt-10">
    <h1 class="text-3xl font-bold mb-6 text-center">Barang dengan Stok Rendah</h1>
    @if($lowStockProducts->count() > 0)
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border-b text-left">No</th>
                    <th class="py-2 px-4 border-b text-left">Nama Barang</th>
                    <th class="py-2 px-4 border-b text-left">Stok</th>
                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $index => $barang)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                        <td class="py-2 px-4 border-b">{{ $barang->nama_barang }}</td>
                        <td class="py-2 px-4 border-b text-red-500 font-bold">{{ $barang->stok }}</td>
                        <td class="p-4 border flex space-x-2">
                            <a href="{{ route('barang.edit', $barang->id) }}" class="bg-yellow-400 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center text-red-500 font-bold">Tidak ada barang dengan stok rendah.</p>
    @endif
</div>
@endsection
