@extends('kasir.layouts.app')

@section('title', 'Data Obat - Toko Obat')
@section('page-title', 'Data Obat')

@section('content')
@if(session('success'))
    <div class="alert success absolute p-4 bg-green-700/50 text-green-500 border rounded-md border-green-600 text-center top-[12%] left-1/2 -translate-x-1/2 z-50 alertAnimate">
        {{ session('success') ?? 'Berhasil!' }}
    </div>
@endif
<!-- Mobile-optimized padding and spacing -->
<div class="p-3 sm:p-4 lg:p-6 space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1 sm:space-y-2">
            <!-- Responsive typography -->
            <h1 class="text-2xl sm:text-3xl font-bold text-foreground">Data Obat</h1>
        </div>
    </div>

    <div class="bg-muted rounded-lg border border-border">
<div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <!-- CHANGE> Added search button next to search input -->
            <form class="flex space-y-3 sm:space-y-0 sm:space-x-3 flex-col sm:flex-row" action="{{ route('kasir.obat') }}">
                <div class="relative flex-1">
                    <input type="text"
                            name="search"
                            value="{{ $search }}"
                            id="search"
                            placeholder="Cari user berdasarkan nama atau kode barcode..."
                            class="kode_barcode w-full px-4 py-3 pl-12 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base min-h-[48px]">
                    <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-muted-foreground"></i>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="submit" name="stok" value="habis"
                            class="grow group relative bg-red-100 hover:bg-red-200 border border-red-300 text-red-700 px-6 py-3 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 flex items-center justify-center font-semibold text-sm transform hover:scale-105 hover:-translate-y-0.5 min-h-[48px] active:scale-95">
                            <span class="hidden sm:inline">Cek Stok Habis</span>
                            <i data-lucide="alert-triangle" class="inline sm:hidden"></i>
                        </button>
                    <button type="submit" class="grow group relative bg-muted hover:bg-muted/80 border border-border text-foreground px-6 py-3 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 flex items-center justify-center font-semibold text-sm transform hover:scale-105 hover:-translate-y-0.5 min-h-[48px] active:scale-95">
                        <span class="hidden sm:inline relative z-10">Cari</span>
                        <i data-lucide="search" class="inline sm:hidden relative z-10"></i>
                    </button>
                    <button onclick="document.querySelector('#search').value = ''; input.closest('form').submit();" class="grow group relative border border-border text-foreground px-6 py-3 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 flex items-center justify-center font-semibold text-sm transform hover:scale-105 hover:-translate-y-0.5 min-h-[48px] active:scale-95">
                        <span class="hidden sm:inline relative z-10">Reset</span>
                        <i data-lucide="x" class="inline sm:hidden relative z-10"></i>
                    </button>
                    <button type="button" id="start-scan" class="grow group relative border border-border text-foreground px-6 py-3 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 flex items-center justify-center font-semibold text-sm transform hover:scale-105 hover:-translate-y-0.5 min-h-[48px] active:scale-95">
                        <span class="hidden sm:inline relative z-10">Scan</span>
                        <i data-lucide="scan" class="inline sm:hidden relative z-10"></i>  
                    </button>
                </div>
            </form>
        </div>

        <div class="p-3 sm:p-6">
            <!-- Mobile-responsive grid layout -->
            @if (count($obats) === 0)
                <h1 class="font-medium text-xl text-foreground text-center">Tidak ada data obat!</h1>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                @foreach($obats as $obat)
                    @php
                        $stockStatus =
                                $obat['stok'] == 0
                                    ? ['label' => 'STOK HABIS', 'class' => 'bg-red-600/20 text-red-600']
                                    : ($obat['stok'] < 50
                                        ? ['label' => 'Stok Rendah', 'class' => 'bg-red-500/10 text-red-500']
                                        : ($obat['stok'] < 100
                                            ? ['label' => 'Stok Sedang', 'class' => 'bg-yellow-500/10 text-yellow-500']
                                            : ['label' => 'Stok Aman', 'class' => 'bg-green-500/10 text-green-500']));
                    @endphp

                    <!-- Mobile-optimized card with better touch targets -->
                    <div class="group relative bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-800 dark:via-slate-900 dark:to-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 sm:p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1 active:scale-[0.98]">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 via-purple-500/5 to-pink-500/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <div class="relative z-10">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300 truncate">{{ $obat['nama'] }}</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Barcode : {{ $obat['kode_barcode'] }}</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Tipe : {{ $obat['tipe']['nama'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 sm:space-y-4">
                                <div class="grid gap-3 sm:gap-4">
                                    <div class="bg-slate-100 dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Stok</span>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 rounded-full {{ $obat['stok'] < 50 ? 'bg-red-500' : ($obat['stok'] < 100 ? 'bg-yellow-500' : 'bg-green-500') }}"></div>
                                                <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $obat['stok'] }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $stockStatus['class'] }} mt-1 inline-block">{{ $stockStatus['label'] }}</span>
                                    </div>
                                </div>
                                <div class="bg-slate-100 dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700">
                                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400 block">Harga Jual</span>
                                    <span class="text-base sm:text-lg font-bold text-green-600 dark:text-green-400">Rp {{ number_format($obat['harga_jual']) }}</span>
                                </div>

                                <div class="bg-gradient-to-r from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 rounded-lg p-3 border border-slate-200 dark:border-slate-600">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Expired</span>
                                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ Carbon\Carbon::parse($obat['expired_at'])->format('d-m-Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute inset-0 rounded-xl border-2 border-transparent group-hover:border-blue-200 dark:group-hover:border-blue-800 transition-colors duration-300"></div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="px-4 md:px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 gap-2 md:gap-0">
                <div class="text-sm text-gray-700 dark:text-gray-300 text-center md:text-left">
                    Menampilkan <span class="font-medium">{{ $obats->firstItem() }}</span> sampai <span class="font-medium">{{ $obats->lastItem() }}</span> dari <span class="font-medium">{{ $obats->total() }}</span> hasil
                </div>

                <!-- Smart Pagination Component -->
                <div class="flex flex-col md:flex-row items-center space-y-3 md:space-y-0 md:space-x-4">
                    <!-- Pagination Controls -->
                    <div class="flex gap-4 items-center flex-col md:flex-row">
                        <div class="flex items-center space-x-1 sm:space-x-2">
                            <!-- First Page -->
                            <button class="group px-2 sm:px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 flex items-center font-medium min-h-[44px] sm:min-h-[48px] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" @disabled($obats->onFirstPage()) onclick="window.location.href='{{ $obats->url(1) }}'">
                                <i data-lucide="chevrons-left" class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-0.5"></i>
                                <span class="hidden xl:inline ml-1">First</span>
                            </button>
    
                            <!-- Previous Page -->
                            <button class="group px-2 sm:px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 flex items-center font-medium min-h-[44px] sm:min-h-[48px] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" @disabled($obats->onFirstPage()) onclick="window.location.href='{{ $obats->previousPageUrl() }}'">
                                <i data-lucide="chevron-left" class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-0.5"></i>
                                <span class="hidden lg:inline ml-1">Prev</span>
                            </button>
    
                            <div class="flex items-center space-x-1">
                                <!-- Current page -->
                                <button class="px-3 py-2.5 text-sm bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium shadow-md min-h-[48px] ring-2 ring-blue-200 dark:ring-blue-800">{{ $obats->currentPage() }}</button>
                            </div>
    
                            <!-- Next Page -->
                            <button class="group px-2 sm:px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 flex items-center font-medium min-h-[44px] sm:min-h-[48px] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" @disabled($obats->onLastPage()) onclick="window.location.href='{{ $obats->nextPageUrl() }}'">
                                <span class="hidden lg:inline mr-1">Next</span>
                                <i data-lucide="chevron-right" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5"></i>
                            </button>
    
                            <!-- Last Page -->
                            <button class="group px-2 sm:px-3 py-2.5 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 flex items-center font-medium min-h-[44px] sm:min-h-[48px] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" @disabled($obats->onLastPage()) onclick="window.location.href='{{ $obats->url($obats->lastPage()) }}'">
                                <span class="hidden xl:inline mr-1">Last</span>
                                <i data-lucide="chevrons-right" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5"></i>
                            </button>
                        </div>
                        <form action="" method="GET" class="flex gap-3 items-center">
                            <span>Jump to page:</span>
                            @if ($search != '')
                            <input type="hidden" name="search" value="{{ $search }}">
                            @endif
                            <input id="inputPage" type="number" name="page" min="1" max="{{ $obats->lastPage() }}" class="max-w-10 py-2 border rounded-md text-center dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-foreground" value="{{ $obats->currentPage() }}" >
                            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 py-[0.675rem] px-3 text-white rounded-md text-sm font-medium active:scale-90 transition-all">Go</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="previewCamera" class="absolute z-50 flex inset-0 hidden bg-black/50">
    <div id="reader" class="m-auto" style="width: 100%; max-width: 400px; display:block;"></div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="{{ asset('js/paginate.js') }}"></script>
<script src="{{ asset('js/kasir/obat/scan.js') }}"></script>
<script src="{{ asset('js/barcode-scanner.js') }}"></script>
@endsection
