@extends('layout.app')

@section('title', 'supplier')

@section('content')
{{-- <div class="flex justify-between suppliers-center mb-4">
    <h2 class="text-lg font-bold">supplier</h2>
    <button onclick="window.location.href='{{ route('supplier.create') }}'" class="bg-blue-500 text-white py-2 px-4 rounded-lg transform transition-transform hover:scale-110">
        + Tambah supplier
    </button>
</div> --}}
<div class="flex justify-between suppliers-center mb-4">
    <h2 class="text-lg font-bold">Supplier</h2>
    <!-- Tombol untuk membuka modal -->
    <button id="openModal" class="bg-blue-500 text-white py-2 px-4 rounded-lg transform transition-transform hover:scale-110">
        + Tambah Supplier
    </button>
</div>

<!-- Modal Tambah-->
<div id="addModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <div class="flex justify-between">
            <h3 class="text-xl font-bold">Tambah Supplier</h3>
            <button id="closeAddModal" class="text-gray-600 font-bold text-xl">×</button>
        </div>
        <!-- Form untuk tambah kategori -->
        <form action="{{ route('supplier.store') }}" method="POST" >
            @csrf
            <div class="mb-4">
                <label for="nama_supplier" class="block text-sm font-medium mb-1">Nama Supplier</label>
                <input type="text" id="nama_supplier" name="nama_supplier"  class="w-full border border-gray-300 p-2 rounded-lg text-sm @error('nama') border-red-500 @enderror" required>
                @error('nama_supplier')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium mb-1"> alamat</label>
                <input type="text" id="alamat" name="alamat"  class="w-full border border-gray-300 p-2 rounded-lg text-sm " required>
            </div>
            <div class="mb-4">
                <label for="no_telepon" class="block text-sm font-medium mb-1"> no_telepon</label>
                <input type="number" id="no_telepon" name="no_telepon"  class="w-full border border-gray-300 p-2 rounded-lg text-sm " required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <div class="flex justify-between">
            <h3 class="text-xl font-bold">Edit Supplier</h3>
            <button id="closeEditModal" class="text-gray-600 font-bold text-xl">×</button>
        </div>
        <!-- Form untuk edit supplier -->
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_nama_supplier" class="block text-sm font-medium mb-1">Nama Supplier</label>
                <input type="text" id="edit_nama_supplier" name="nama_supplier" class="w-full border border-gray-300 p-2 rounded-lg text-sm" required>
            </div>
            <div class="mb-4">
                <label for="edit_alamat" class="block text-sm font-medium mb-1">Alamat</label>
                <input type="text" id="edit_alamat" name="alamat" class="w-full border border-gray-300 p-2 rounded-lg text-sm" required>
            </div>
            <div class="mb-4">
                <label for="edit_no_telepon" class="block text-sm font-medium mb-1">No. Telepon</label>
                <input type="number" id="edit_no_telepon" name="no_telepon" class="w-full border border-gray-300 p-2 rounded-lg text-sm" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</div>

<div class="mb-4 flex justify-left">
    <input
        type="text"
        id="searchInput"
        placeholder="Cari supplier..."
        class="w-80 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    />
</div>
<div class="bg-white rounded-lg shadow">
    <table class="table-auto w-full border-collapse">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-4 border">Nama supplier</th>
                <th class="p-4 border">Alamat</th>
                <th class="p-4 border">No. Telp</th>
                <th class="p-4 border">Aksi</th>
            </tr>
        </thead>
        <tbody id="TableBody">
            @foreach($suppliers as $supplier)
            <tr>
                <td class="p-4 border">{{ $supplier->nama_supplier }}</td>
                <td class="p-4 border">{{ $supplier->alamat }}</td>
                <td class="p-4 border">{{ $supplier->no_telepon }}</td>
                <td class="p-4 border flex space-x-2">
                    <button class="editButton bg-yellow-400 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110" 
                        data-id="{{ $supplier->id }}" 
                        data-nama="{{ $supplier->nama_supplier }}" 
                        data-alamat="{{ $supplier->alamat }}" 
                        data-telepon="{{ $supplier->no_telepon }}">
                        Edit
                    </button>
                    <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event);">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>

        // Modal Edit
        const editModal = document.getElementById('editModal');
        const closeEditModal = document.getElementById('closeEditModal');
        const editForm = document.getElementById('editForm');

        closeEditModal.addEventListener('click', () => editModal.classList.add('hidden'));

            document.querySelectorAll('.editButton').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const nama = button.getAttribute('data-nama');
                    const alamat = button.getAttribute('data-alamat');
                    const telepon = button.getAttribute('data-telepon');

                    document.getElementById('edit_nama_supplier').value = nama;
                    document.getElementById('edit_alamat').value = alamat;
                    document.getElementById('edit_no_telepon').value = telepon;

                    editForm.action = `/supplier/${id}`; // Update action URL
                    editModal.classList.remove('hidden');
                });
        });
        
        // Search
        document.getElementById('searchInput').addEventListener('input', function () {
        const query = this.value;

        fetch(`{{ route('supplier.cari') }}?search=${query}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('TableBody');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                // Jika tidak ada data, tambahkan pesan ke dalam tabel
                    const emptyRow = `
                        <tr>
                            <td class="p-4 border text-center" colspan="3">Tidak ada data yang ditemukan</td>
                        </tr>
                    `;
                    tableBody.innerHTML = emptyRow;
                    } else {
                    // Jika ada data, tampilkan seperti biasa
                    data.forEach(supplier => {
                        const row = `
                        <tr>
                                <td class="p-4 border">${supplier.nama_supplier}</td>
                                <td class="p-4 border">${supplier.alamat}</td>
                                <td class="p-4 border">${supplier.no_telepon}</td>
                                <td class="p-4 border flex space-x-2">
                                    <a href="/supplier/${supplier.id}/edit" class="bg-yellow-400 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110">Edit</a>
                                    <form action="/supplier/${supplier.id}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event);">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                }
            });
        });
    </script>
</div>
@endsection
