@extends('layout.app')

@section('title', 'Transaksi penjualan')

@section('content')
    <div class="container mx-auto p-2">

        @if(session('success'))
            <div class="alert alert-success bg-green-500 text-white p-2 rounded mb-2">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger bg-red-500 text-white p-2 rounded mb-2">
                {{ session('error') }}
            </div>
        @endif
        <div class="mt-4 mb-2 flex justify-end">
            <a href="{{ route('laporan-penjualan.index') }}" class="bg-green-500 text-white px-4 py-2 rounded-md text-sm">Lihat Laporan Pembelian</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
            <div class="bg-white shadow-lg rounded-lg p-4">
                <div class="text-md font-semibold mb-2">Tambah Barang</div>
                
                <div class="mb-2 flex items-center">
                    <label for="barang" class="text-gray-700 font-semibold text-sm w-32">Nama Barang</label>
                    <input type="text" id="barang" name="barang" class="w-full mt-2 p-1 border rounded-md focus:ring-blue-500 focus:border-blue-500" list="barang-list">
                            <datalist id="barang-list">
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->nama_barang }}" data-harga="{{ $barang->harga_jual }}">
                                @endforeach
                            </datalist>
                </div>

                <div class="mb-2 flex items-center">
                    <label class="text-gray-700 font-semibold text-sm w-32">Harga Satuan</label>
                    <input type="text" id="harga-satuan" class="w-full p-1 border rounded-md bg-gray-100 text-sm" readonly>
                </div>
                
                <div class="mb-2 flex items-center">
                    <label class="text-gray-700 font-semibold text-sm w-32">QTY</label>
                    <div class="flex items-center w-full">
                        <button type="button" id="decrease-qty" class="bg-gray-300 px-2 py-1 rounded-l-md text-sm">-</button>
                        <input type="number" id="qty" value="1" class="w-full text-center border-t border-b p-1 text-sm">
                        <button type="button" id="increase-qty" class="bg-gray-300 px-2 py-1 rounded-r-md text-sm">+</button>
                    </div>
                </div>
                
                <div class="flex justify-between">
                    <button type="button" id="add-item" class="bg-blue-500 text-white px-2 py-1 rounded-md text-sm">Tambah</button>
                </div>
            </div>
            
            <div class="bg-white shadow-lg rounded-lg p-1 mt-1">
                <table class="w-full border-collapse border border-gray-300 text-xs">
                    <thead>
                        <tr>
                            <th class="border px-1 py-1">No</th>
                            <th class="border px-1 py-1">Nama Produk</th>
                            <th class="border px-1 py-1">QTY</th>
                            <th class="border px-1 py-1">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="barang-rows">
                        {{-- <tr>
                            
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="bg-white shadow-lg rounded-lg p-4 mt-4">
            <div class="mb-2 flex items-center">
                <label class="text-gray-700 font-semibold text-sm w-32">Total Belanja</label>
                <input type="text" id="grand-total" class="w-full p-1 border rounded-md text-sm" readonly>
            </div>
            
            <div class="mb-2 flex items-center">
                <label class="text-gray-700 font-semibold text-sm w-32">Dibayarkan</label>
                <input type="number" id="bayar" class="w-full p-1 border rounded-md text-sm" placeholder="Masukkan nominal bayar">
            </div>

            <div class="flex justify-between">
                <button type="button" id="btn-bayar" class=" w-100 bg-blue-500 text-white font-semibold py-1 px-2 rounded-md text-sm">
                    bayar
                </button>
                <button type="button" id="btn-reset" class=" bg-green-500 text-white font-semibold py-1 px-2 rounded-md text-sm">
                    reset
                </button>

            </div>
            
            
            
            
            <div class="mt-2 flex items-center">
                <label class="text-gray-700 font-semibold text-sm w-32">Uang Kembalian</label>
                <input type="text" id="kembalian" class="w-full p-1 border rounded-md bg-gray-100 text-sm" readonly>
            </div>
        </div>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Data barang dari Laravel
    const barangData = {
        @foreach($barangs as $barang)
            "{{ $barang->nama_barang }}" : {{ $barang->harga_jual }},
        @endforeach
    };

    // Ambil elemen
    const barangInput = document.getElementById('barang');
    const qtyInput = document.getElementById('qty');
    const hargaSatuanInput = document.getElementById('harga-satuan');
    const tabelBarang = document.getElementById('barang-rows');
    const grandTotalInput = document.getElementById('grand-total');
    const bayarInput = document.getElementById('bayar');
    const kembalianInput = document.getElementById('kembalian');
    const btnbayar = document.getElementById('btn-bayar');
    const btnTambah = document.getElementById('add-item');
    const btnReset = document.getElementById('btn-reset');

    // Event untuk mengisi harga otomatis ketika barang dipilih
    barangInput.addEventListener('input', function () {
        const barang = barangInput.value.trim();
        if (barangData[barang]) {
            hargaSatuanInput.value = 'Rp ' + barangData[barang].toLocaleString('id-ID');
        } else {
            hargaSatuanInput.value = '';
        }
    });

        // Event listener tombol reset
        btnReset.addEventListener('click', function () {
        // Reset input barang, harga, qty
        barangInput.value = '';
        hargaSatuanInput.value = '';
        qtyInput.value = '1';

        // Hapus semua baris di tabel transaksi
        tabelBarang.innerHTML = '';

        // Reset total belanja, pembayaran, dan kembalian
        grandTotalInput.value = '';
        bayarInput.value = '';
        kembalianInput.value = '';
    });


    

    // Tambahkan barang ke tabel
    btnTambah.addEventListener('click', function () {
        const barang = barangInput.value.trim();
        const qty = parseInt(qtyInput.value);

        if (!barang || isNaN(qty) || qty < 1) {
            alert("Harap pilih barang dan masukkan jumlah yang valid.");
            return;
        }

        if (!barangData[barang]) {
            alert("Barang tidak ditemukan.");
            return;
        }

// Cek apakah barang sudah ada di tabel
        const existingRow = [...tabelBarang.querySelectorAll("tr")].find(row => 
             row.dataset.barang === barang
        );

        if (existingRow) {
             alert("Barang sudah ada dalam daftar.");
               return;
            }

        const harga = barangData[barang];
        const subtotal = harga * qty;

        // Tambahkan barang ke tabel
        const row = document.createElement('tr');
        row.dataset.barang = barang; // Tambahkan ini
        row.innerHTML = `
            <td class="border px-2 py-1">${barang}</td>
            <td class="border px-2 py-1">Rp ${harga.toLocaleString('id-ID')}</td>
            <td class="border px-2 py-1">
                <input type="number" class="qty w-full p-1 border rounded-md" value="${qty}" min="1">
            </td>
            <td class="border px-2 py-1 subtotal">Rp ${subtotal.toLocaleString('id-ID')}</td>
            <td class="border px-2 py-1">
                <button type="button" class="remove-barang bg-red-500 text-white p-1 rounded-md">Hapus</button>
            </td>
        `;

        tabelBarang.appendChild(row);

        // Reset input setelah barang ditambahkan
        barangInput.value = '';
        qtyInput.value = '1';
        hargaSatuanInput.value = '';

        // Update total
        updateGrandTotal();

        // Event untuk hapus barang dari tabel
        row.querySelector('.remove-barang').addEventListener('click', function () {
            row.remove();
            updateGrandTotal();
        });

        // Event untuk update subtotal ketika qty diubah
        row.querySelector('.qty').addEventListener('input', function () {
            let newQty = parseInt(this.value);
            if (isNaN(newQty) || newQty < 1) newQty = 1;
            this.value = newQty;

            const newSubtotal = harga * newQty;
            row.querySelector('.subtotal').innerText = `Rp ${newSubtotal.toLocaleString('id-ID')}`;
            updateGrandTotal();
        });
    });

    // Fungsi update total belanja
    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(subtotal => {
            const harga = parseFloat(subtotal.innerText.replace(/[^\d]/g, ''));
            if (!isNaN(harga)) {
                total += harga;
            }
        });
        grandTotalInput.value = 'Rp ' + total.toLocaleString('id-ID');
    }



    // Event listener tombol bayar
    btnbayar.addEventListener('click', function () {
        const grandTotal = parseFloat(grandTotalInput.value.replace(/[^\d]/g, ''));
        const bayar = parseFloat(bayarInput.value);

        if (isNaN(bayar) || bayar <= 0) {
            alert("Masukkan nominal bayar yang valid!");
            return;
        }

        if (bayar < grandTotal) {
            alert("Uang tidak cukup!");
            return;
        }

        bayarInput.value = 'Rp ' + bayarInput

        const kembalian = bayar - grandTotal;
        kembalianInput.value = 'Rp ' + kembalian.toLocaleString('id-ID');

        // Simpan transaksi ke backend
        simpanTransaksi(grandTotal, bayar, kembalian);
    });

    // Fungsi untuk menyimpan transaksi ke backend
    function simpanTransaksi(grandTotal, bayar, kembalian) {
        const transaksi = [];
        document.querySelectorAll('#barang-rows tr').forEach(row => {
        if (!row.querySelector('.qty')) return; // Lewati baris tanpa input qty

            const namaBarang = row.children[0]?.innerText?.trim();
            const qty = parseInt(row.querySelector('.qty')?.value);
            const subtotal = parseFloat(row.children[3]?.innerText.replace(/[^\d]/g, ''));

        if (!namaBarang || isNaN(qty) || isNaN(subtotal)) return; // Cek validitas data

            transaksi.push({ namaBarang, qty, subtotal });
    });

    function cetakStruk(transaksi, grandTotal, bayar, kembalian) {
    const win = window.open('', '_blank');

    // Ambil tanggal sekarang
    const now = new Date();
    const tanggal = now.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
    const jam = now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
    });

    let html = `
        <html>
        <head>
            <title>Struk Pembayaran</title>
            <style>
                @media print {
                    @page {
                        size: 58mm auto;
                        margin: 5mm;
                    }
                }
                body {
                    font-family: monospace;
                    font-size: 10px;
                    padding: 5px;
                    width: 58mm;
                }
                h2 {
                    text-align: center;
                    margin: 5px 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    padding: 2px 0;
                    text-align: left;
                    font-size: 10px;
                    word-break: break-word;
                }
                th.nama, td.nama { width: 45%; }
                th.qty, td.qty { width: 15%; text-align: center;}
                th.subtotal, td.subtotal { width: 40%; text-align: right; }
                .total {
                    font-weight: bold;
                    border-top: 1px dashed #000;
                    margin-top: 5px;
                    padding-top: 5px;
                }
                hr {
                    border: none;
                    border-top: 1px dashed #000;
                    margin: 5px 0;
                }
                .center {
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <h2>HARSINDO BAN</h2>
            <div class="center">Jl. Pat- Gabus Km. 01 , Pati<br>Telp: 0812-3456-7890</div>
            <div class="center">Tanggal: ${tanggal} ${jam}</div>
            <hr>
            <table>
                <thead>
                    <tr>
                        <th class="nama">Nama</th>
                        <th class="qty">Qty</th>
                        <th class="subtotal">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
    `;

    transaksi.forEach(item => {
        html += `
            <tr>
                <td class="nama">${item.namaBarang}</td>
                <td class="qty">${item.qty}</td>
                <td class="subtotal">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
            <hr>
            <div class="total">Total : Rp ${grandTotal.toLocaleString('id-ID')}</div>
            <div>Bayar : Rp ${bayar.toLocaleString('id-ID')}</div>
            <div>Kembali: Rp ${kembalian.toLocaleString('id-ID')}</div>
            <hr>
            <div class="center">~ Terima kasih ~</div>
        </body>
        </html>
    `;

    win.document.write(html);
    win.document.close();
    win.focus();
    win.print();
}





        console.log("Data yang akan dikirim: ", transaksi);
        fetch("{{ route('transaksi.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                items: transaksi,
                grandTotal,
                bayar,
                kembalian
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            cetakStruk(transaksi, grandTotal, bayar, kembalian); // <-- panggil cetak
            location.reload(); // optional: refresh setelah print
        })
        .catch(error => {
            alert('Terjadi kesalahan, coba lagi!');
            // console.error(error);
        });
    }
});


</script>
@endsection
