@extends('layout.app')  

@section('content')
{{-- <div class="flex justify-between items-center mb-4">
    <h2 class="text-lg font-bold">Kategori</h2>
    <button onclick="window.location.href='{{ route('kategori.create') }}'" class="bg-blue-500 text-white py-2 px-4 rounded-lg transform transition-transform hover:scale-110">
        + Tambah Kategori  
    </button>
</div> --}}

<div class="flex justify-between suppliers-center mb-4">
    <h2 class="text-lg font-bold">Kategori</h2>
    <!-- Tombol untuk membuka modal -->
    <button id="openModal" class="bg-blue-500 text-white py-2 px-4 rounded-lg transform transition-transform hover:scale-110">
        + Tambah Kategori
    </button>
</div>


<!-- Modal -->
<div id="addModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <div class="flex justify-between">
            <h3 class="text-xl font-bold">Tambah Kategori</h3>
            <button id="closeAddModal" class="text-gray-600 font-bold text-xl">×</button>
        </div>
        <!-- Form untuk tambah kategori -->
        <form action="{{ route('kategori.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium mb-1">Nama Kategori</label>
                <input type="text" id="nama" name="nama"  class="w-full border border-gray-300 p-2 rounded-lg text-sm @error('nama') border-red-500 @enderror" required>
                @error('nama')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <div class="flex justify-between">
            <h3 class="text-xl font-bold">Edit User</h3>
            <button id="closeEditModal" class="text-gray-600 font-bold text-xl">×</button>
        </div>
        <!-- Form untuk edit Barang -->
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_nama" class="block text-sm font-medium mb-1">Nama kategori</label>
                <input type="text" id="edit_nama" name="nama" class="w-full border border-gray-300 p-2 rounded-lg text-sm" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</div>

<!-- Kolom Pencarian -->
<div class="mb-4 flex justify-left">
    <input
        type="text"
        id="searchKategoriInput"
        placeholder="Cari kategori..."
        class="w-80 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    />
</div>

<div class="bg-white rounded-lg shadow">
    <table class="table-auto w-full border-collapse" id="kategoriTable">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-4 border">Nama Kategori</th>
                <th class="p-4 border">Aksi</th>
            </tr>
        </thead>
        <tbody id="kategoriTableBody">
            @foreach($kategoris as $kategori)
            <tr>
                <td class="p-4 border">{{ $kategori->nama }}</td>
                <td class="p-4 border flex justify-end space-x-2">
                    <button class="editButton bg-yellow-400 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110" 
                        data-id="{{ $kategori->id }}" 
                        data-nama="{{ $kategori->nama }}">
                        Edit
                    </button>
                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event);">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchKategoriInput').addEventListener('input', function () {
        const query = this.value;

        fetch(`{{ route('kategori.cari') }}?search=${query}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('kategoriTableBody');
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
                data.forEach(kategori => {
                    const row = `
                    <tr>
                        <td class="p-4 border">${kategori.nama}</td>
                        <td class="p-4 border flex space-x-2">
                            <a href="/kategori/${kategori.id}/edit" class="bg-yellow-400 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110">Edit</a>
                            <form action="/kategori/${kategori.id}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event);">
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

                // Modal Edit
        const editModal = document.getElementById('editModal');
        const closeEditModal = document.getElementById('closeEditModal');
        const editForm = document.getElementById('editForm');

        closeEditModal.addEventListener('click', () => editModal.classList.add('hidden'));

            document.querySelectorAll('.editButton').forEach(button => {
                button.addEventListener('click', () => {
                    // Ambil data dari atribut data-*
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');

            // Isi form dengan data kategori
            document.getElementById('edit_nama').value = nama;

            // Set action URL untuk form edit
            editForm.action = `/kategori/${id}`; // Sesuaikan route dengan kebutuhan
            editModal.classList.remove('hidden');
                });
        });
</script>
@endsection

