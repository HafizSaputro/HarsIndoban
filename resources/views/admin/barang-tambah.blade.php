<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Barang - Pop-Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-gray-100 flex items-center justify-center h-screen">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
      <!-- Pop-up modal -->
      <div class="bg-white rounded-lg w-[400px] p-6 shadow-lg">
        <h2 class="text-lg font-bold text-gray-700 text-center mb-4">Barang</h2>
        <form action="#" method="POST">
          <!-- Kategori -->
          <div class="mb-4">
            <label for="kategori" class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
            <input type="text" id="kategori" name="kategori" placeholder="Masukkan Kategori" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-300" />
          </div>
          <!-- Nama Barang -->
          <div class="mb-4">
            <label for="nama_barang" class="block text-sm font-medium text-gray-600 mb-1">Nama Barang</label>
            <input type="text" id="nama_barang" name="nama_barang" placeholder="Masukkan Nama Barang" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-300" />
          </div>
          <!-- Harga Barang -->
          <div class="mb-4">
            <label for="harga_barang" class="block text-sm font-medium text-gray-600 mb-1">Harga Barang</label>
            <input type="text" id="harga_barang" name="harga_barang" placeholder="Masukkan Harga Barang" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-300" />
          </div>
          <!-- Stok Barang -->
          <div class="mb-4">
            <label for="stok_barang" class="block text-sm font-medium text-gray-600 mb-1">Stok Barang</label>
            <input type="text" id="stok_barang" name="stok_barang" placeholder="Masukkan Stok Barang" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-300" />
          </div>
          <!-- Tombol Simpan -->
          <div class="text-center">
            <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 transition-transform transform hover:scale-105">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
