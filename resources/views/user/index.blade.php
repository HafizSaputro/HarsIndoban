@extends('layout.app')

@section('title', 'user')

@section('content')
<div class="flex justify-between suppliers-center mb-4">
    <h2 class="text-lg font-bold">User</h2>
    <!-- Tombol untuk membuka modal -->
    <button id="openModal" class="bg-blue-500 text-white py-2 px-4 rounded-lg transform transition-transform hover:scale-110">
        + Tambah User
    </button>
</div>


<!-- Modal -->
<div id="addModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <div class="flex justify-between">
            <h3 class="text-xl font-bold">Tambah User</h3>
            <button id="closeAddModal" class="text-gray-600 font-bold text-xl">×</button>
        </div>
        <!-- Form untuk tambah supplier -->
        <form action="{{ route('user.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium mb-1">Username</label>
                <input type="text" id="username" name="username"  class="w-full border border-gray-300 p-2 rounded-lg text-sm @error('username') border-red-500 @enderror" required>
                @error('username')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <input type="text" id="password" name="password" class="w-full border border-gray-300 p-2 rounded-lg text-sm " required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium mb-1">Role</label>
                <select id="role" name="role" class="w-full border border-gray-300 p-2 rounded-lg text-sm ">
                    <option value="">Pilih Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
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
                <label for="edit_username" class="block text-sm font-medium mb-1">Usernam</label>
                <input type="text" id="edit_username" name="username" class="w-full border border-gray-300 p-2 rounded-lg text-sm" required>
            </div>
            <div class="mb-4">
                <label for="edit_password" class="block text-sm font-medium mb-1">Password</label>
                <input type="text" id="edit_password" name="password" class="w-full border border-gray-300 p-2 rounded-lg text-sm" required>
            </div>
            <div class="mb-4">
                <label for="edit_role" class="block text-sm font-medium mb-1">Role</label>
                <select id="edit_role" name="role" class="w-full border border-gray-300 p-2 rounded-lg text-sm" required>
                    <option value="">Pilih role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</div>


<!-- Kolom Pencarian dan Filter -->
<div class="mb-4 flex justify-left space-x-4">
    <input
        type="text"
        id="searchInput"
        placeholder="Cari user..."
        class="w-80 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    />

    <select
        id="roleFilter"
        class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    >
        <option value="">Semua Role</option>
        <option value="admin">Admin</option>
        <option value="user">Kasir</option>
    </select>
</div>


<div class="bg-white rounded-lg shadow">
    <table class="table-auto w-full border-collapse">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-4 border">Username</th>
                <th class="p-4 border">Role</th>
                <th class="p-4 border" style="width: 30%;">Aksi</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            @foreach($users as $user)
            <tr>
                <td class="p-4 border">{{ $user->username }}</td>
                <td class="p-4 border">{{ $user->role }}</td>
                <td class="p-4 border flex space-x-2">
                    <button class="editButton bg-yellow-400 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110" 
                        data-id="{{ $user->id }}" 
                        data-username="{{ $user->username }}" 
                        data-password="{{ $user->password }}" 
                        data-role="{{ $user->role }}">
                        Edit
                    </button>
                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event);">
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
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const tableBody = document.getElementById('userTableBody');
    
        function fetchData() {
            const query = searchInput.value;
            const role = roleFilter.value;
    
            fetch(`{{ route('user.cari') }}?search=${query}&role=${role}`)
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = '';
    
                    if (data.length === 0) {
                        const emptyRow = `
                            <tr>
                                <td class="p-4 border text-center" colspan="3">Tidak ada data yang ditemukan</td>
                            </tr>
                        `;
                        tableBody.innerHTML = emptyRow;
                    } else {
                        data.forEach(user => {
                            const row = `
                                <tr>
                                    <td class="p-4 border">${user.username}</td>
                                    <td class="p-4 border">${user.role}</td>
                                    <td class="p-4 border flex space-x-2">
                                        <button class="editButton bg-yellow-400 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110"
                                            data-id="${user.id}"
                                            data-username="${user.username}"
                                            data-password="${user.password}"
                                            data-role="${user.role}">
                                            Edit
                                        </button>
                                        <form action="/user/${user.id}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event);">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-lg transform transition-transform hover:scale-110">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            `;
                            tableBody.innerHTML += row;
                        });
                        // Re-bind tombol edit setelah render ulang
                        rebindEditButtons();
                    }
                });
        }
    
        // Event listener untuk filter dan search input
        searchInput.addEventListener('input', fetchData);
        roleFilter.addEventListener('change', fetchData);
    
        // Re-bind edit button karena element baru di-render ulang
        function rebindEditButtons() {
            document.querySelectorAll('.editButton').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const username = button.getAttribute('data-username');
                    const password = button.getAttribute('data-password');
                    const role = button.getAttribute('data-role');
    
                    document.getElementById('edit_username').value = username;
                    document.getElementById('edit_password').value = password;
                    document.getElementById('edit_role').value = role;
                    editForm.action = `/user/${id}`;
                    editModal.classList.remove('hidden');
                });
            });
        }
    
        rebindEditButtons();
    </script>
    
</div>
@endsection
