@extends('kasir.layouts.app')

@section('title', 'Transaksi - Kasir')

@section('content')
    @if (session('error'))
        <div
            class="alert error absolute p-4 bg-red-700/50 text-red-500 border rounded-md border-red-600 text-center top-[12%] left-1/2 -translate-x-1/2 z-50 alertAnimate">
            {{ session('error') ?? 'Error!' }}
        </div>
    @endif
    <div class="rounded-lg border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950">
        <div class="p-3 flex flex-col md:flex-row gap-2 md:items-center md:justify-between">
            <form method="GET" class="flex flex-col sm:flex-row flex-wrap gap-2 w-full md:w-auto items-end">
                <!-- Input pencarian -->
                <div class="flex-1">
                    <input name="search" id="search" value="{{ $search }}" placeholder="Cari kode transaksi..."
                        class="py-[0.725rem] w-full h-10 rounded-md border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 text-sm focus:ring-2 focus:ring-primary" />
                </div>

                <!-- Filter rentang tanggal -->
                <div class="flex gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="h-10 rounded-md border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 text-sm" />
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="h-10 rounded-md border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 text-sm" />
                </div>

                {{-- <!-- Filter cepat -->
                <div>
                    <select name="filter" onchange="this.form.submit()"
                        class="h-10 rounded-md border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3 text-sm">
                        <option value="">-- Filter --</option>
                        <option value="harian" {{ request('filter') == 'harian' ? 'selected' : '' }}>Harian</option>
                        <option value="mingguan" {{ request('filter') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                        <option value="bulanan" {{ request('filter') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div> --}}
                <div>
                    <select name="status"
                        class="h-10 rounded-md border border-neutral-200 dark:border-neutral-800
        bg-white dark:bg-neutral-900 px-3 text-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="SUCCESS" {{ request('status') == 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
                        <option value="RETURN" {{ request('status') == 'RETURN' ? 'selected' : '' }}>RETURN</option>
                        <option value="VOID" {{ request('status') == 'VOID' ? 'selected' : '' }}>BATAL</option>
                    </select>
                </div>

                <!-- Tombol Cari -->
                <div class="flex gap-2">
                    <button type="button" id="btn-profit"
                        class="py-2 px-4 rounded-md border border-neutral-200 dark:border-neutral-800 
           bg-gradient-to-r from-purple-600 to-purple-700 text-white active:scale-90 transition-all">
                        📊 Cek Keuntungan
                    </button>
                    <button type="submit"
                        class="py-2 px-4 rounded-md border border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-blue-600 to-blue-700 text-white active:scale-90 transition-all">
                        Cari
                    </button>

                    <!-- Tombol Export Excel -->
                    <a href="{{ route('kasir.transaksi.export', request()->query()) }}"
                        class="py-2 px-4 rounded-md border border-neutral-200 dark:border-neutral-800 bg-gradient-to-r from-green-600 to-green-700 text-white active:scale-90 transition-all">
                        📤 Export Excel
                    </a>
                </div>
            </form>

        </div>
        {{-- TABLE WRAPPER --}}
        <div class="relative overflow-x-auto rounded-lg border border-neutral-200 dark:border-neutral-800">

            <table class="min-w-[720px] w-full text-sm">
                {{-- TABLE HEADER --}}
                <thead class="bg-neutral-50 dark:bg-neutral-900 sticky top-0 z-10">
                    <tr>
                        <th class="text-center py-3 px-2 text-xs font-semibold">
                            Kode
                        </th>

                        <th class="hidden sm:table-cell text-center py-3 px-2 text-xs font-semibold">
                            Obat
                        </th>

                        <th class="hidden md:table-cell text-center py-3 px-2 text-xs font-semibold">
                            Jumlah
                        </th>

                        <th class="text-center py-3 px-2 text-xs font-semibold">
                            Total
                        </th>

                        <th class="hidden sm:table-cell text-center py-3 px-2 text-xs font-semibold">
                            Status
                        </th>

                        <th class="hidden lg:table-cell text-center py-3 px-2 text-xs font-semibold">
                            Waktu
                        </th>

                        <th class="text-center py-3 px-2 text-xs font-semibold">
                            Aksi
                        </th>
                    </tr>
                </thead>

                {{-- TABLE BODY --}}
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr class="border-b dark:border-neutral-800">

                            {{-- KODE --}}
                            <td class="text-center px-3 py-2 font-medium">
                                {{ $transaksi->kode }}
                            </td>

                            {{-- OBAT --}}
                            <td class="hidden sm:table-cell text-center px-3 py-2">
                                {{ $transaksi->name }}
                            </td>

                            {{-- JUMLAH --}}
                            <td class="hidden md:table-cell text-center px-3 py-2">
                                {{ $transaksi->qty }}
                            </td>

                            {{-- TOTAL --}}
                            <td class="text-center px-3 py-2 font-semibold">
                                {{ formatRupiah($transaksi->total_transaksi) }}
                            </td>

                            {{-- STATUS --}}
                            <td class="hidden sm:table-cell text-center px-3 py-2">
                                @if ($transaksi->status === 'SUCCESS')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700
                                dark:bg-green-900/40 dark:text-green-400">
                                        SUCCESS
                                    </span>
                                @elseif ($transaksi->status === 'VOID')
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700
                                dark:bg-red-900/40 dark:text-red-400">
                                        BATAL
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700
                                dark:bg-yellow-900/40 dark:text-yellow-400">
                                        RETURN
                                    </span>
                                @endif
                            </td>

                            {{-- WAKTU --}}
                            <td class="hidden lg:table-cell text-center px-3 py-2">
                                {{ $transaksi->created_at->format('d/m/Y H:i') }}
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center px-3 py-2">
                                <div class="flex flex-col sm:flex-row gap-2 justify-center">

                                    <button data-id="{{ $transaksi->id }}"
                                        class="px-3 py-1.5 rounded-md border border-neutral-200
                                       dark:border-neutral-800 hover:bg-neutral-50
                                       dark:hover:bg-neutral-800 btn-detail">
                                        Detail
                                    </button>

                                    @if ($transaksi->status === 'SUCCESS')
                                        <button
                                            onclick="openVoidModal({{ $transaksi->id }}, '{{ $transaksi->kode }}', event)"
                                            class="px-3 py-1.5 rounded-md bg-red-600 text-white hover:bg-red-700">
                                            Batal
                                        </button>

                                        <button onclick="openReturnModal({{ $transaksi->id }}, '{{ $transaksi->kode }}')"
                                            class="px-3 py-1.5 rounded-md bg-yellow-500 text-white hover:bg-yellow-600">
                                            Return
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-muted-foreground">
                                Tidak ada data transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    <!-- ================= MODAL DETAIL TRANSAKSI ================= -->
    <div id="trx-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-overlay></div>

        <div
            class="relative mx-auto mt-16 w-[95%] max-w-3xl bg-white dark:bg-neutral-950
              rounded-lg shadow-xl flex flex-col max-h-[90vh]">

            <!-- HEADER -->
            <div class="px-5 py-4 border-b dark:border-neutral-800">
                <h3 class="font-semibold text-lg">Detail Transaksi</h3>
                <p class="text-sm text-muted-foreground">Kode: <span id="trxKode"></span></p>
                <p class="text-sm text-muted-foreground">Kasir: <span id="trxKasir"></span></p>
                <p class="text-sm text-muted-foreground">Waktu: <span id="trxWaktu"></span></p>
                <p class="text-sm text-muted-foreground">Void At: <span id="trxVoidAt"></span></p>
                <p class="text-sm text-muted-foreground">Return At: <span id="trxReturnAt"></span></p>
                <div class="mt-1">Status :
                    <span id="trxStatus" class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold"></span>
                </div>
            </div>

            <!-- BODY -->
            <div class="p-4 overflow-y-auto flex-1">

                <!-- TABEL ITEM -->
                <div class="overflow-x-auto overflow-y-auto border rounded-md dark:border-neutral-800">
                    <table class="min-w-full text-sm">
                        <thead class="bg-neutral-100 dark:bg-neutral-900">
                            <tr>
                                <th class="px-3 py-2 text-left">Produk</th>
                                <th class="px-3 py-2 text-center">Qty</th>
                                <th class="px-3 py-2 text-center">Return</th>
                                <th class="px-3 py-2 text-right">Harga</th>
                                <th class="px-3 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="trx-items"></tbody>
                    </table>
                </div>

                <!-- TOTAL -->
                <div class="mt-4 space-y-1 text-sm">
                    <div class="flex justify-between font-semibold">
                        <span>Total</span>
                        <span id="trxTotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Dibayar</span>
                        <span id="trxTotalBayar">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kembalian</span>
                        <span id="trxTotalKembalian">Rp 0</span>
                    </div>
                </div>

                <!-- INFO VOID -->
                <div id="void-info" class="hidden mt-4 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 text-sm">
                    <p class="font-semibold text-red-600">TRANSAKSI DI BATALKAN</p>
                    <p>Alasan: <span id="voidReason"></span></p>
                    <p>Oleh: <span id="voidBy"></span></p>
                </div>

                <!-- INFO RETURN -->
                <div id="return-section" class="hidden mt-4">
                    <h4 class="font-semibold mb-2">Riwayat Return</h4>
                    <div class="overflow-x-auto border rounded-md dark:border-neutral-800">
                        <table class="min-w-full text-sm">
                            <thead class="bg-neutral-100 dark:bg-neutral-900">
                                <tr>
                                    <th class="px-3 py-2">Produk</th>
                                    <th class="px-3 py-2 text-center">Qty</th>
                                    <th class="px-3 py-2 text-right">Nominal</th>
                                    <th class="px-3 py-2">Alasan</th>
                                    <th class="px-3 py-2">Oleh</th>
                                </tr>
                            </thead>
                            <tbody id="returnTable"></tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="px-4 py-3 border-t flex justify-between dark:border-neutral-800">
                <button onclick="closeAllModals()" class="px-4 py-2 rounded-md bg-gray-500 text-white">
                    Tutup
                </button>
                <button onclick="printStruk()" class="px-4 py-2 rounded-md bg-blue-600 text-white">
                    Cetak Struk
                </button>
            </div>

        </div>
    </div>


    <div id="profit-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" data-profit-overlay></div>

        <div
            class="relative mx-auto mt-20 w-[92%] max-w-xl rounded-lg 
                border border-neutral-200 dark:border-neutral-800 
                bg-white dark:bg-neutral-950 shadow-xl">

            <div class="px-4 py-3 border-b font-semibold">
                📊 Rincian Keuntungan
            </div>

            <div class="p-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span>Total Penjualan</span>
                    <span id="profitTotalJual">Rp 0</span>
                </div>
                <div class="flex justify-between">
                    <span>Total Modal</span>
                    <span id="profitTotalModal">Rp 0</span>
                </div>
                <div class="flex justify-between font-semibold text-green-600">
                    <span>Total Keuntungan</span>
                    <span id="profitTotalUntung">Rp 0</span>
                </div>
                <div class="flex justify-between text-xs text-muted-foreground">
                    <span>Margin</span>
                    <span id="profitMargin">0%</span>
                </div>
            </div>

            <div class="px-4 pb-4 flex justify-end gap-2">
                <button id="profit-close" class="px-4 py-2 rounded-md bg-gray-500 text-white">Tutup</button>
            </div>
        </div>
    </div>
    <div id="void-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeVoidModal()"></div>

        <div
            class="relative mx-auto mt-24 w-[92%] max-w-md rounded-lg
               border border-neutral-200 dark:border-neutral-800
               bg-white dark:bg-neutral-950 shadow-xl p-5">

            <h3 class="text-lg font-semibold mb-1">Void Transaksi</h3>
            <p class="text-sm text-muted-foreground mb-3">
                Kode: <span id="voidKode"></span>
            </p>

            <form method="POST" id="voidForm" class="space-y-4">
                @csrf

                <textarea name="void_reason" required placeholder="Alasan void transaksi..."
                    class="w-full rounded-md border border-neutral-200 dark:border-neutral-800
                       bg-white dark:bg-neutral-900 p-3 text-sm"></textarea>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeVoidModal()"
                        class="px-4 py-2 rounded-md border dark:border-neutral-700">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                        Void
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="return-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeReturnModal()"></div>

        <div
            class="relative mx-auto mt-16 w-[92%] max-w-2xl rounded-lg
        border border-neutral-200 dark:border-neutral-800
        bg-white dark:bg-neutral-950 shadow-xl flex flex-col max-h-[90vh]">

            <!-- HEADER -->
            <div class="px-5 py-4 border-b dark:border-neutral-800">
                <h3 class="text-lg font-semibold">Return Transaksi</h3>
                <p class="text-sm text-muted-foreground">
                    Kode: <span id="returnKode"></span>
                </p>
            </div>

            <!-- BODY -->
            <form method="POST" id="returnForm" class="p-5 overflow-y-auto flex-1 space-y-4">
                @csrf

                <div class="overflow-x-auto rounded-md border dark:border-neutral-800">
                    <table class="min-w-full text-sm">
                        <thead class="bg-neutral-50 dark:bg-neutral-900">
                            <tr>
                                <th class="px-3 py-2 text-left">Produk</th>
                                <th class="px-3 py-2 text-center">Dibeli</th>
                                <th class="px-3 py-2 text-center">Sisa</th>
                                <th class="px-3 py-2 text-center">Return</th>
                            </tr>
                        </thead>
                        <tbody id="return-items">
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted-foreground">
                                    Memuat item...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <label class="text-sm font-medium">Alasan Return</label>
                    <textarea name="reason" required
                        class="w-full mt-1 rounded-md border dark:border-neutral-800
                    bg-white dark:bg-neutral-900 p-3 text-sm"></textarea>
                </div>

                <!-- FOOTER -->
                <div class="flex justify-end gap-2 pt-3 border-t dark:border-neutral-800">
                    <button type="button" onclick="closeReturnModal()"
                        class="px-4 py-2 rounded-md border dark:border-neutral-700">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-yellow-500 text-white hover:bg-yellow-600">
                        Proses Return
                    </button>
                </div>
            </form>
        </div>
    </div>




    <script>
        // 1. Definisikan data global
        window.transaksis = @json($transaksis->items());

        // 2. Base URL (Menghapus garis miring di akhir jika ada untuk mencegah //)
        window.BASE_URL = "{{ url('/') }}".replace(/\/$/, "") + "/";

        // 3. Helper Format Angka (Jika belum ada di file lain)
        window.formatNumber = function(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        console.log("Data Transaksi Berhasil Dimuat:", window.transaksis);
    </script>
    <script src="{{ asset('js/kasir/transaksi/script.js') }}"></script>
@endsection
