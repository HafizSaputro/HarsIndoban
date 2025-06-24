@extends('layout.app')

@section('title', 'Barang')
@section('content')

<div class="flex justify-between barangs-center mb-4">
    <h2 class="text-lg font-bold">barang</h2>
    <!-- Tombol untuk membuka modal -->
    <button id="openModal" class="bg-blue-500 text-white py-2 px-4 rounded-lg transform transition-transform hover:scale-110">
        + Tambah barang
    </button>
</div>

<!-- Modal Tambah -->
<div id="addModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <div class="flex justify-between">
            <h3 class="text-xl font-bold">Tambah Barang</h3>
            <button id="closeAddModal" class="text-gray-600 font-bold text-xl">×</button>
        </div>
        <!-- Form untuk tambah kategori -->
        <form action="{{ route('barang.store') }}" method="POST" >
            @csrf
            <div class="mb-4">
                <label for="nama_barang" class="block text-sm font-medium mb-1">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang"  class="w-full border border-gray-300 p-2 rounded-lg text-sm @error('nama_barang') border-red-500 @enderror" required>
                @error('nama_barang')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="kategori_id" class="block text-sm font-medium mb-1">Kategori</label>
                <select id="kategori_id" name="kategori_id" class="w-full border border-gray-300 p-2 rounded-lg text-sm">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="harga_jual" class="block text-sm font-medium mb-1"> harga_jual</label>
                <input type="number" id="harga_jual" name="harga_jual"  class="w-full border border-gray-300 p-2 rounded-lg text-sm " required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <div class="flex justify-between">
            <h3 class="text-xl font-bold">Edit Barang</h3>
            <button id="closeEditModal" class="text-gray-600 font-bold text-xl">×</button>
        </div>
        <!-- Form untuk edit Barang -->
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_nama_barang" class="block text-sm font-medium mb-1">Nama Barang</label>
                <input type="text" id="edit_nama_barang" name="nama_barang" class="w-full border border-gray-300 p-2 rounded-lg text-sm" required>
            </div>
            <div class="mb-4">
                <label for="edit_kategori" class="block text-sm font-medium mb-1">Nama Kategori</label>
                <select id="edit_kategori" name="kategori_id" class="w-full border border-gray-300 p-2 rounded-lg text-sm">
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="edit_stok" class="block text-sm font-medium mb-1" >Stok</label>
                <input type="number" id="edit_stok" name="stok" class="w-full border border-gray-300 p-2 rounded-lg text-sm" readonly>
            </div>
            <div class="mb-4">
                <label for="edit_harga" class="block text-sm font-medium mb-1">Harga Jual</label>
                <input type="number" id="edit_harga" name="harga" class="w-full border border-gray-300 p-2 rounded-lg text-sm" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</div>

<!-- Filter Kategori -->
<div class="mb-4 flex justify-left space-x-4">
    <select id="kategoriFilter" class="border border-gray-300 rounded-lg p-2 text-sm">
        <option value="">Semua Kategori</option>
        @foreach($kategoris as $kategori)
            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
        @endforeach
    </select>

    <input
        type="text"
        id="searchInput"
        placeholder="Cari barang..."
        class="w-80 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    />
</div>


<div class="bg-white rounded-lg shadow">
    <table class="table-auto w-full border-collapse" id="barangTable">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-4 border">Nama Barang</th>
                <th class="p-4 border">Kategori</th>
                <th class="p-4 border">Stok</th>
                <th class="p-4 border">Harga Jual</th>
                <th class="p-4 border">Aksi</th>
            </tr>
        </thead>
        <tbody id="barangTableBody">
            @foreach($barangs as $barang)
            <tr>
                <td class="p-4 border">{{ $barang->nama_barang }}</td>
                <td class="p-4 border">{{ $barang->kategori->nama ?? 'Tidak ada' }}</td>
                <td class="p-4 border">{{ $barang->stok }}</td>
                <td class="p-4 border">{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                <td class="p-4 border flex space-x-2">
                    <button class="editButton bg-yellow-400 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110" 
                        data-id="{{ $barang->id }}" 
                        data-nama_barang="{{ $barang->nama_barang }}" 
                        data-kategori="{{ $barang->kategori->nama  }}" 
                        data-stok="{{ $barang->stok }}"
                        data-harga_jual="{{ number_format($barang->harga_jual, 0, ',', '.') }}">
                        Edit
                    </button>
                    <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event);">
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
const searchInput = document.getElementById('searchInput');
const kategoriFilter = document.getElementById('kategoriFilter');

function fetchBarang() {
    const query = searchInput.value;
    const kategoriId = kategoriFilter.value;

    fetch(`{{ route('barang.cari') }}?search=${query}&kategori_id=${kategoriId}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('barangTableBody');
            tableBody.innerHTML = '';

            if (data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td class="p-4 border text-center" colspan="5">Tidak ada data yang ditemukan</td>
                    </tr>`;
            } else {
                data.forEach(barang => {
                    const row = `
                        <tr>
                            <td class="p-4 border">${barang.nama_barang}</td>
                            <td class="p-4 border">${barang.kategori.nama}</td>
                            <td class="p-4 border">${barang.stok}</td>
                            <td class="p-4 border">${barang.harga_jual.toLocaleString()}</td>
                            <td class="p-4 border flex space-x-2">
                                <a href="/barang/${barang.id}/edit" class="bg-yellow-400 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110">Edit</a>
                                <form action="/barang/${barang.id}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event);">
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
}

searchInput.addEventListener('input', fetchBarang);
kategoriFilter.addEventListener('change', fetchBarang);


            // Modal Edit
        const editModal = document.getElementById('editModal');
        const closeEditModal = document.getElementById('closeEditModal');
        const editForm = document.getElementById('editForm');

        closeEditModal.addEventListener('click', () => editModal.classList.add('hidden'));

            document.querySelectorAll('.editButton').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const namaBarang = button.getAttribute('data-nama_barang');
                    const kategori = button.getAttribute('data-kategori');
                    const stok = button.getAttribute('data-stok');
                    const hargaJual = button.getAttribute('data-harga_jual');

                    document.getElementById('edit_nama_barang').value = namaBarang;
                    document.getElementById('edit_kategori').value = kategori;
                    document.getElementById('edit_stok').value = stok;
                    document.getElementById('edit_harga').value = hargaJual;

                    editForm.action = `/barang/${id}`; // Update action URL
                    editModal.classList.remove('hidden');
                });
        });

</script>
@endsection
