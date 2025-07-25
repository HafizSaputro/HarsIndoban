<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  </head>
  <body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-blue-500 p-4 flex items-center justify-between w-full fixed top-0 left-0 z-40 md:relative md:z-10">
      <div class="flex items-center">
        <!-- Hamburger -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-white text-xl mr-4 md:hidden">
          <i class="fas fa-bars"></i>
        </button>
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10" />
      </div>
      <div class="flex items-center">
        <div class="relative">
          <a href="{{ route('barang.lowstock') }}" class="text-white text-lg">
            <i class="fas fa-bell"></i>
          </a>
          @if($lowStockCount > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">
              {{ $lowStockCount }}
            </span>
          @endif
        </div>
        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="ml-4" id="logout-form">
          @csrf
          <button 
            type="button" 
            onclick="confirmLogout()" 
            class="text-white bg-red-500 hover:bg-red-600 text-lg p-2 rounded-full flex items-center justify-center">
            <i class="fas fa-door-open"></i>
          </button>
        </form>
      </div>
    </header>

    <div class="min-h-screen flex flex-col md:flex-row ">

      <!-- Overlay (Mobile) -->
      <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false" 
        class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
        x-transition>
      </div>

      <!-- Sidebar -->
      <aside  :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"  class="fixed transform md:translate-x-0 transition-transform duration-200 ease-in-out md:relative  top-0 left-0 w-64 bg-white border-r  z-30 p-4">
        <nav class="px-4 space-y-4 text-sm">
          @if(Auth::user()->role === 'admin')
            <div>
              <a
                href="{{ url('dashboard') }}"
                class="flex items-center gap-2 px-2 py-2 rounded hover:bg-gray-100 {{ request()->is('dashboard') ? 'bg-blue-100 text-blue-500' : '' }}"
              >
                <i class="fa fa-home"></i> Dashboard
              </a>
            </div>
          @endif

          <div x-data="{ open: false }">
            <button
              @click="open = !open"
              class="flex items-center justify-between w-full px-2 py-2 font-bold text-gray-500 hover:text-black"
            >
              <span class="flex items-center gap-2">
                <i class="fa fa-database"></i> DATA
              </span>
              <svg
                :class="{ 'rotate-180': open }"
                class="w-4 h-4 transition-transform duration-200"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>

            <ul
              x-show="open"
              x-transition
              class="space-y-1 pl-4 mt-1"
            >
              @if(Auth::user()->role === 'admin')
                <li>
                  <a
                    href="{{ url('kategori') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded hover:bg-gray-100 {{ request()->is('kategori') ? 'bg-blue-100 text-blue-500' : '' }}"
                  >
                    <i class="fa fa-tags"></i> Kategori
                  </a>
                </li>
                <li>
                  <a
                    href="{{ url('supplier') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded hover:bg-gray-100 {{ request()->is('supplier') ? 'bg-blue-100 text-blue-500' : '' }}"
                  >
                    <i class="fa fa-truck"></i> Supplier
                  </a>
                </li>
                <li>
                  <a
                    href="{{ url('user') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded hover:bg-gray-100 {{ request()->is('user') ? 'bg-blue-100 text-blue-500' : '' }}"
                  >
                    <i class="fa fa-users"></i> User
                  </a>
                </li>
              @endif
              <li>
                <a
                  href="{{ url('barang') }}"
                  class="flex items-center gap-2 px-2 py-2 rounded hover:bg-gray-100 {{ request()->is('barang') ? 'bg-blue-100 text-blue-500' : '' }}"
                >
                  <i class="fa fa-box"></i> Barang
                </a>
              </li>
            </ul>
          </div>

          <!-- TRANSAKSI (Dropdown) -->
          <div
            x-data="{ open: {{ request()->is('transaksi') || request()->is('barang-masuk') ? 'true' : 'false' }} }"
          >
            <button
              @click="open = !open"
              class="flex items-center justify-between w-full px-2 py-2 font-bold text-gray-500 hover:text-black"
            >
              <span class="flex items-center gap-2">
                <i class="fa fa-exchange-alt"></i> TRANSAKSI
              </span>
              <svg
                :class="{ 'rotate-180': open }"
                class="w-4 h-4 transition-transform duration-200"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>

            <ul
              x-show="open"
              x-transition
              class="space-y-1 pl-4 mt-1"
            >
              <li>
                <a
                  href="{{ url('transaksi') }}"
                  class="flex items-center gap-2 px-2 py-2 rounded hover:bg-gray-100 {{ request()->is('transaksi') ? 'bg-blue-100 text-blue-500' : '' }}"
                >
                  <i class="fa fa-shopping-cart"></i> Transaksi Penjualan
                </a>
              </li>
              @if(Auth::user()->role === 'admin')
                <li>
                  <a
                    href="{{ url('barang-masuk') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded hover:bg-gray-100 {{ request()->is('barang-masuk') ? 'bg-blue-100 text-blue-500' : '' }}"
                  >
                    <i class="fa fa-arrow-down"></i> Barang Masuk
                  </a>
                </li>
              @endif
            </ul>
          </div>

          <!-- LAPORAN (Dropdown) -->
          @if(Auth::user()->role === 'admin')
            <div
              x-data="{ open: {{ request()->is('laporan-penjualan') || request()->is('laporan-pembelian') ? 'true' : 'false' }} }"
            >
              <button
                @click="open = !open"
                class="flex items-center justify-between w-full px-2 py-2 font-bold text-gray-500 hover:text-black mt-4"
              >
                <span class="flex items-center gap-2">
                  <i class="fa fa-file-alt"></i> LAPORAN
                </span>
                <svg
                  :class="{ 'rotate-180': open }"
                  class="w-4 h-4 transition-transform duration-200"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>

              <ul
                x-show="open"
                x-transition
                class="space-y-1 pl-4 mt-1"
              >
                <li>
                  <a
                    href="{{ url('laporan-penjualan') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded hover:bg-gray-100 {{ request()->is('laporan-penjualan') ? 'bg-blue-100 text-blue-500' : '' }}"
                  >
                    <i class="fa fa-chart-line"></i> Penjualan
                  </a>
                </li>
                <li>
                  <a
                    href="{{ url('laporan-pembelian') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded hover:bg-gray-100 {{ request()->is('laporan-pembelian') ? 'bg-blue-100 text-blue-500' : '' }}"
                  >
                    <i class="fa fa-clipboard-list"></i> Pembelian
                  </a>
                </li>
              </ul>
            </div>
          @endif
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 px-4 pt-5 pb-6 overflow-auto mt-14 md:mt-0">
        @if(session('success'))
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            {{ session('error') }}
          </div>
        @endif

        @yield('content')
      </main>
    </div>

    <script>
      function confirmLogout() {
        if (confirm("Apakah Anda yakin ingin logout?")) {
          document.getElementById('logout-form').submit();
        }
      }

      function confirmDelete(event) {
        if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) {
          event.preventDefault();
          return false;
        }
        return true;
      }

    document.addEventListener("DOMContentLoaded", function () {
        const openModalBtn = document.getElementById("openModal");
        const closeModalBtn = document.getElementById("closeAddModal");
        const modal = document.getElementById("addModal");

        openModalBtn.addEventListener("click", function () {
            modal.classList.remove("hidden");
        });

        closeModalBtn.addEventListener("click", function () {
            modal.classList.add("hidden");
        });

        // Tutup modal jika klik di luar isi modal
        window.addEventListener("click", function (e) {
            if (e.target === modal) {
                modal.classList.add("hidden");
            }
        });
    });

    </script>
  </body>
</html>
