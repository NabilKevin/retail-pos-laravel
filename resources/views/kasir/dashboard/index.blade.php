@extends('kasir.layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
@if(session('error'))
    <div class="alert error absolute p-4 bg-red-700/50 text-red-500 border rounded-md border-red-600 text-center top-[12%] left-1/2 -translate-x-1/2 z-50 alertAnimate">
        {{ session('error') ?? 'Error!' }}
    </div>
@endif
  <div class="p-3 sm:p-4 lg:p-6 space-y-4 sm:space-y-6">
    <div class="space-y-1 sm:space-y-2">
        <h1 class="text-2xl sm:text-3xl font-bold text-foreground">Dashboard</h1>
        <p class="text-sm sm:text-base text-muted-foreground">Selamat datang di sistem kasir Toko Obat</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
        <div class="group relative bg-gradient-to-br from-green-500/10 via-green-600/5 to-transparent border border-green-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-green-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Penjualan Hari Ini</p>
                    <p class="text-xl sm:text-3xl font-bold text-foreground mt-1 truncate">{{ formatRupiah($penjualanHariIni) }}</p>
                    <p class="text-xs text-{{ $totalKenaikanPenjualan >= 0 ? 'green' : 'red' }}-500 mt-1 flex items-center">
                        @if($totalKenaikanPenjualan > 0)
                            <i data-lucide="trending-up" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                            <span class="truncate">+{{ $totalKenaikanPenjualan }}% dari kemarin</span>
                        @elseif ($totalKenaikanPenjualan < 0)
                            <i data-lucide="trending-down" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                            <span class="truncate">{{ $totalKenaikanPenjualan }}% dari kemarin</span>
                        @else
                            <span class="truncate">Tidak ada perubahan dari kemarin</span>
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/25 flex-shrink-0 ml-3">
                    <i data-lucide="trending-up" class="h-6 w-6 sm:h-7 sm:w-7 text-white"></i>
                </div>
            </div>
        </div>

        <div class="group relative bg-gradient-to-br from-yellow-500/10 via-yellow-600/5 to-transparent border border-yellow-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-yellow-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Transaksi</p>
                    <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1 truncate">{{ $totalTransaksi }}</p>
                    <p class="text-xs text-yellow-500 mt-1 flex items-center">
                        @if($totalKenaikanTransaksi > 0)
                            <i data-lucide="trending-up" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                            <span class="truncate">+{{ $totalKenaikanTransaksi }}% dari kemarin</span>
                        @elseif ($totalKenaikanTransaksi < 0)
                            <i data-lucide="trending-down" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                            <span class="truncate">{{ $totalKenaikanTransaksi }}% dari kemarin</span>
                        @else
                            <span class="truncate">Tidak ada perubahan dari kemarin</span>
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/25 flex-shrink-0 ml-3">
                    <i data-lucide="alert-triangle" class="h-6 w-6 sm:h-7 sm:w-7 text-white"></i>
                </div>
            </div>
        </div>

        <div class="group relative bg-gradient-to-br from-purple-500/10 via-purple-600/5 to-transparent border border-purple-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-purple-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Item terjual</p>
                    <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1 truncate">{{ $totalItemTerjual }}</p>
                    <p class="text-xs text-purple-500 mt-1 flex items-center">
                        @if($totalKenaikanItemTerjual > 0)
                            <i data-lucide="trending-up" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                            <span class="truncate">+{{ $totalKenaikanItemTerjual }}% dari kemarin</span>
                        @elseif ($totalKenaikanItemTerjual < 0)
                            <i data-lucide="trending-down" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                            <span class="truncate">{{ $totalKenaikanItemTerjual }}% dari kemarin</span>
                        @else
                            <span class="truncate">Tidak ada perubahan dari kemarin</span>
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/25 flex-shrink-0 ml-3">
                    <i data-lucide="users" class="h-6 w-6 sm:h-7 sm:w-7 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-900/50 rounded-xl p-4 sm:p-6 border border-slate-200/50 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-foreground">Transaksi Terbaru</h3>
        </div>
        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full align-middle">
            @if (count($transaksis) === 0)
                <h1 class="my-4 font-medium text-xl text-foreground text-center">Tidak ada data transaksi!</h1>
            @else
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-700">
                            <th class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50 rounded-l-lg">ID</th>
                            <th class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50">Obat</th>
                            <th class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50 hidden sm:table-cell">Jumlah</th>
                            <th class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50">Total</th>
                            <th class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50 rounded-r-lg hidden md:table-cell">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksis as $transaksi)
                            <tr class="border-b border-slate-200/50 dark:border-slate-700/50 hover:bg-white/50 dark:hover:bg-slate-800/30 transition-colors duration-200 active:bg-slate-100/50 dark:active:bg-slate-700/50">
                                <td class="py-3 sm:py-4 px-2">
                                    <span class="text-xs sm:text-sm font-mono font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded">#{{ $transaksi->id }}</span>
                                </td>
                                <td class="py-3 sm:py-4 px-2">
                                    <div class="flex items-center">
                                        <div class="min-w-0">
                                            <span class="text-xs sm:text-sm font-medium text-foreground block truncate">{{ $transaksi->obat->nama }}</span>
                                            <span class="text-xs text-muted-foreground sm:hidden">{{ $transaksi->qty }} unit</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-2 hidden sm:table-cell">
                                    <span class="text-sm text-foreground bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-full">{{ $transaksi->qty }} unit</span>
                                </td>
                                <td class="py-3 sm:py-4 px-2">
                                    <span class="text-xs sm:text-sm font-semibold text-green-600 dark:text-green-400">{{ formatRupiah($transaksi->subtotal) }}</span>
                                </td>
                                <td class="py-3 sm:py-4 px-2 hidden md:table-cell">
                                    <span class="text-sm text-muted-foreground">{{ timeAgo($transaksi->transaction->created_at) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection