@extends('kasir.layouts.app')

@section('title', 'POS - Kasir')

@section('content')
    <div id="alert"
        class="hidden alert success fixed p-3 bg-green-700/50 text-green-500 border rounded-md border-green-600 text-center 
            top-20 left-1/2 -translate-x-1/2 z-50 w-[90%] md:w-auto">
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- === KERANJANG (Mobile = Atas, Desktop = Kanan) === --}}
        <aside class="order-1 lg:order-2 lg:col-span-1">
            <div
                class="rounded-lg border border-neutral-200 dark:border-neutral-800 
                    bg-white dark:bg-neutral-950 flex flex-col max-h-[85vh]">

                <div class="px-4 py-3 border-b border-neutral-200 dark:border-neutral-800 font-medium">
                    Keranjang
                </div>

                <div class="px-4 py-3 overflow-x-auto flex-1">
                    <table class="w-full text-sm" id="carts">
                        <thead class="text-xs text-neutral-600 dark:text-neutral-300">
                            <tr class="border-b border-neutral-200 dark:border-neutral-800">
                                <th class="text-center py-2">Produk</th>
                                <th class="text-center py-2">Qty</th>
                                <th class="text-center py-2">Harga</th>
                                <th class="text-center py-2">Total</th>
                                <th class="text-center py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800"></tbody>
                    </table>

                    <h1 class="text-center mt-3 font-medium alert-text-table">
                        Tidak ada produk di keranjang!
                    </h1>
                </div>

                <div class="px-4 flex flex-col gap-2">
                    <input id="inputTotalBayar" placeholder="Total dibayar..." type="text"
                        class="h-10 rounded-md border border-neutral-200 dark:border-neutral-800 
                           bg-white dark:bg-neutral-900 px-3 text-sm focus:ring-2 focus:ring-primary">
                </div>

                <div class="p-4 space-y-1 text-sm">
                    <div class="flex justify-between font-semibold">
                        <span>Total</span>
                        <span>Rp <span id="textTotal">0</span></span>
                    </div>

                    <div class="flex justify-between mt-4">
                        <span>Total bayar</span>
                        <span>Rp <span id="textTotalBayar">0</span></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Total kembalian</span>
                        <span>Rp <span id="textTotalKembalian">0</span></span>
                    </div>
                </div>

                <div class="p-4 pt-0">
                    <button id="btnBayar"
                        class="w-full h-10 rounded-md bg-gradient-to-r from-blue-600 to-blue-700 
                           text-white active:scale-95 transition">
                        Bayar
                    </button>
                </div>
            </div>
        </aside>


        {{-- === KATALOG (Mobile = Bawah, Desktop = Kiri) === --}}
        <section class="order-2 lg:order-1 lg:col-span-2 flex flex-col gap-4">

            {{-- Search + Scan --}}
            <div
                class="rounded-lg border border-neutral-200 dark:border-neutral-800 
                    bg-white dark:bg-neutral-950 p-3">
                <div class="flex flex-col gap-2">
                    <input id="search" name="search" placeholder="Masukkan nama obat/kode barcode..."
                        class="h-10 rounded-md border border-neutral-200 dark:border-neutral-800 
                           bg-white dark:bg-neutral-900 px-3 text-sm focus:ring-2 focus:ring-primary" />

                    <button id="start-scan"
                        class="h-10 rounded-md border border-neutral-200 dark:border-neutral-800 
                           hover:bg-neutral-50 dark:hover:bg-neutral-800">
                        Scan
                    </button>
                </div>
            </div>

            {{-- Katalog --}}
            <div
                class="rounded-lg border border-neutral-200 dark:border-neutral-800 
                    bg-white dark:bg-neutral-950 overflow-hidden flex flex-col h-[60vh] md:h-[70vh] xl:h-[75vh]">

                <div class="px-4 py-2 text-sm font-medium border-b border-neutral-200 dark:border-neutral-800">
                    Katalog
                </div>

                <div id="tableObats" class="divide-y divide-neutral-100 dark:divide-neutral-800 overflow-y-auto px-2">
                    @foreach ($obats as $obat)
                        <div class="flex items-center justify-between px-2 py-4">
                            <div>
                                <div class="font-medium">{{ $obat['nama'] }}</div>
                                <div class="font-medium mb-1">{{ formatRupiah($obat['harga']) }}</div>
                                <div class="text-xs text-neutral-500">Stok: {{ $obat['stok'] }}</div>
                                <div class="text-xs text-neutral-500">
                                    Expired:
                                    {{ $obat['expired_at'] ? \Carbon\Carbon::parse($obat['expired_at'])->format('d M Y') : 'Tidak ada tanggal expired' }}
                                </div>
                                <div class="text-xs text-neutral-500">Barcode: {{ $obat['kode_barcode'] }}</div>
                               
                                @if ($obat['expired_at'] && \Carbon\Carbon::parse($obat['expired_at'])->isPast())
                                    <div class="text-xs text-red-600 font-semibold">* Obat ini sudah kadaluarsa</div>
                                @endif
                            </div>

                            @php

                                $isExpired = $obat['expired_at'] ? \Carbon\Carbon::parse($obat['expired_at'])->isPast() : false;

                                $isDisabled = $obat['stok'] == 0 || $isExpired;
                            @endphp

                            <button data-id="{{ $obat['id'] }}" data-stok="{{ $obat['stok'] }}"
                                data-expired="{{ $obat['expired_at'] }}"
                                class="btn-tambah text-sm px-3 py-1.5 rounded-md border
        border-neutral-200 dark:border-neutral-800
        transition
        {{ $isDisabled
            ? 'opacity-50 cursor-not-allowed bg-neutral-100 dark:bg-neutral-800'
            : 'hover:bg-neutral-50 dark:hover:bg-neutral-800' }}"
                                {{ $isDisabled ? 'disabled' : '' }}>
                                @if ($isExpired)
                                    Expired
                                @elseif ($obat['stok'] == 0)
                                    Habis
                                @else
                                    Tambah
                                @endif
                            </button>


                        </div>
                    @endforeach
                </div>
            </div>

        </section>
    </div>

    {{-- Scanner --}}
    <div id="previewCamera" class="fixed z-50 inset-0 hidden bg-black/50 flex items-center justify-center">
        <div id="reader" class="w-[90%] max-w-[400px] bg-white rounded-md overflow-hidden shadow-lg">
        </div>
    </div>

    <script>
        const obats = @json($obats);
    </script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="{{ asset('js/kasir/pos/tambah-data.js') }}"></script>
    <script src="{{ asset('js/barcode-scanner.js') }}"></script>
    <script src="{{ asset('js/kasir/pos/scan.js') }}"></script>
    <script src="{{ asset('js/kasir/pos/getData.js') }}"></script>
    <script src="{{ asset('js/kasir/pos/storeData.js') }}"></script>

@endsection
