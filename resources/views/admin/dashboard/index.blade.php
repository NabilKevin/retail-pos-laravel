@extends('admin.layouts.app')

@section('title', 'Dashboard - Toko Obat')
@section('page-title', 'Dashboard')

@section('content')
    @if (session('error'))
        <div
            class="alert error absolute p-4 bg-red-700/50 text-red-500 border rounded-md border-red-600 text-center top-[12%] left-1/2 -translate-x-1/2 z-50 alertAnimate">
            {{ session('error') ?? 'Error!' }}
        </div>
    @endif
    <div class="p-3 sm:p-4 lg:p-6 space-y-4 sm:space-y-6">
        <div class="space-y-1 sm:space-y-2">
            <h1 class="text-2xl sm:text-3xl font-bold text-foreground">Dashboard</h1>
            <p class="text-sm sm:text-base text-muted-foreground">Selamat datang di sistem admin Toko Obat</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
            <div
                class="group relative bg-gradient-to-br from-blue-500/10 via-blue-600/5 to-transparent border border-blue-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Obat</p>
                        <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1 truncate">{{ $totalObat ?? 0 }}</p>
                        <p class="text-xs text-blue-500 mt-1 flex items-center">
                            @if (($totalKenaikanObat ?? 0) > 0)
                                <i data-lucide="trending-up" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                                <span class="truncate">+{{ $totalKenaikanObat }}% dari bulan lalu</span>
                            @elseif (($totalKenaikanObat ?? 0) < 0)
                                <i data-lucide="trending-down" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                                <span class="truncate">{{ $totalKenaikanObat ?? 0 }}% dari bulan lalu</span>
                            @else
                                <span class="truncate">Tidak ada perubahan dari bulan lalu</span>
                            @endif
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25 flex-shrink-0 ml-3">
                        <i data-lucide="package" class="h-6 w-6 sm:h-7 sm:w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-gradient-to-br from-green-500/10 via-green-600/5 to-transparent border border-green-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-green-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-muted-foreground font-medium">Penjualan Hari Ini</p>
                        <p class="text-xl sm:text-3xl font-bold text-foreground mt-1 truncate">
                            {{ formatRupiah($penjualanHariIni ?? 0) }}</p>
                        <p
                            class="text-xs text-{{ ($totalKenaikanPenjualan ?? 0) >= 0 ? 'green' : 'red' }}-500 mt-1 flex items-center">
                            @if (($totalKenaikanPenjualan ?? 0) > 0)
                                <i data-lucide="trending-up" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                                <span class="truncate">+{{ $totalKenaikanPenjualan }}% dari kemarin</span>
                            @elseif (($totalKenaikanPenjualan ?? 0) < 0)
                                <i data-lucide="trending-down" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                                <span class="truncate">{{ $totalKenaikanPenjualan ?? 0 }}% dari kemarin</span>
                            @else
                                <span class="truncate">Tidak ada perubahan dari kemarin</span>
                            @endif
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/25 flex-shrink-0 ml-3">
                        <i data-lucide="trending-up" class="h-6 w-6 sm:h-7 sm:w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-gradient-to-br from-yellow-500/10 via-yellow-600/5 to-transparent border border-yellow-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-yellow-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-muted-foreground font-medium">Stok Menipis</p>
                        <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1 truncate">{{ $totalStokMenipis ?? 0 }}</p>
                        <p class="text-xs text-yellow-500 mt-1 flex items-center">
                            @if ($totalStokMenipis > 0)
                                <i data-lucide="alert-triangle" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                                <span class="truncate">Perlu perhatian</span>
                            @else
                                <span class="truncate">Semua stok dalam kondisi aman</span>
                            @endif
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/25 flex-shrink-0 ml-3">
                        <i data-lucide="alert-triangle" class="h-6 w-6 sm:h-7 sm:w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-gradient-to-br from-red-500/10 via-red-600/5 to-transparent border border-red-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-red-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-muted-foreground font-medium">Obat Expired</p>
                        <p class="text-2xl sm:text-3xl font-bold text-red-600 mt-1">
                            {{ $totalExpired ?? 0 }}
                        </p>
                        <p class="text-xs text-red-500 mt-1 flex items-center">
                            <i data-lucide="alert-octagon" class="h-3 w-3 mr-1"></i>
                            Segera ditangani
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/25">
                        <i data-lucide="skull" class="h-6 w-6 text-white"></i>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-gradient-to-br from-orange-500/10 via-orange-600/5 to-transparent border border-orange-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-orange-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-muted-foreground font-medium">Expired H-7</p>
                        <p class="text-2xl sm:text-3xl font-bold text-orange-600 mt-1">
                            {{ $totalExpiredH7 ?? 0 }}
                        </p>
                        <p class="text-xs text-orange-500 mt-1 flex items-center">
                            <i data-lucide="clock" class="h-3 w-3 mr-1"></i>
                            7 hari ke depan
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/25">
                        <i data-lucide="hourglass" class="h-6 w-6 text-white"></i>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-gradient-to-br from-yellow-500/10 via-yellow-600/5 to-transparent border border-yellow-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-yellow-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-muted-foreground font-medium">Expired H-30</p>
                        <p class="text-2xl sm:text-3xl font-bold text-yellow-600 mt-1">
                            {{ $totalExpiredH30 ?? 0 }}
                        </p>
                        <p class="text-xs text-yellow-500 mt-1 flex items-center">
                            <i data-lucide="calendar" class="h-3 w-3 mr-1"></i>
                            30 hari ke depan
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/25">
                        <i data-lucide="calendar-days" class="h-6 w-6 text-white"></i>
                    </div>
                </div>
            </div>
            {{-- Total Keuntungan Bersih --}}
            <div
                class="group relative bg-gradient-to-br from-green-500/10 via-green-600/5 to-transparent border border-green-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-green-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Keuntungan Bersih</p>
                        <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1 truncate">
                            {{ formatRupiah($totalKeuntungan ?? 0) }}</p>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/25 flex-shrink-0 ml-3">
                        <i data-lucide="dollar-sign" class="h-6 w-6 sm:h-7 sm:w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-gradient-to-br from-red-500/10 via-red-600/5 to-transparent border border-red-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-red-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Modal</p>
                        <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1 truncate">
                            {{ formatRupiah($totalModal ?? 0) }}</p>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/25 flex-shrink-0 ml-3">
                        <i data-lucide="dollar-sign" class="h-6 w-6 sm:h-7 sm:w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-gradient-to-br from-purple-500/10 via-purple-600/5 to-transparent border border-purple-500/20 rounded-xl p-4 sm:p-6 hover:shadow-lg hover:shadow-purple-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total User</p>
                        <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1 truncate">{{ $totalUser ?? 0 }}</p>
                        <p class="text-xs text-purple-500 mt-1 flex items-center">
                            @if (($totalKenaikanUser ?? 0) > 0)
                                <i data-lucide="trending-up" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                                <span class="truncate">+{{ $totalKenaikanUser }}% dari kemarin</span>
                            @elseif (($totalKenaikanUser ?? 0) < 0)
                                <i data-lucide="trending-down" class="h-3 w-3 mr-1 flex-shrink-0"></i>
                                <span class="truncate">{{ $totalKenaikanUser ?? 0 }}% dari kemarin</span>
                            @else
                                <span class="truncate">Tidak ada perubahan dari bulan lalu</span>
                            @endif
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/25 flex-shrink-0 ml-3">
                        <i data-lucide="users" class="h-6 w-6 sm:h-7 sm:w-7 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Grafik Penjualan (Line Chart) -->
            <div
                class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-900/50 rounded-xl p-4 sm:p-6 border border-slate-200/50 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-base sm:text-lg font-semibold text-foreground">Total Penjualan (Mingguan)</h3>
                </div>
                <div class="h-48 sm:h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Grafik Modal (Bar Chart) -->
            <div
                class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-900/50 rounded-xl p-4 sm:p-6 border border-slate-200/50 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-base sm:text-lg font-semibold text-foreground">Modal (Mingguan)</h3>
                </div>
                <div class="h-48 sm:h-64">
                    <canvas id="modalChart"></canvas>
                </div>
            </div>

            <!-- Grafik Keuntungan (Area Chart) -->
            <div
                class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-900/50 rounded-xl p-4 sm:p-6 border border-slate-200/50 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-base sm:text-lg font-semibold text-foreground">Keuntungan (Mingguan)</h3>
                </div>
                <div class="h-48 sm:h-64">
                    <canvas id="profitChart"></canvas>
                </div>
            </div>

            <!-- Rasio Keuntungan (Donut Chart) -->
            <div
                class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-900/50 rounded-xl p-4 sm:p-6 border border-slate-200/50 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-base sm:text-lg font-semibold text-foreground">Rasio Keuntungan</h3>
                </div>
                <div class="h-48 sm:h-64 flex items-center justify-center">
                    <canvas id="ratioChart"></canvas>
                </div>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-900/50 rounded-xl p-4 sm:p-6 border border-slate-200/50 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h3 class="text-base sm:text-lg font-semibold text-foreground">Overview Inventory</h3>
                <span
                    class="text-xs sm:text-sm text-muted-foreground bg-slate-200/50 dark:bg-slate-700/50 px-2 py-1 rounded-full">
                    {{ $totalStokObat }} total
                </span>
            </div>

                <div class="space-y-3 sm:space-y-4">
                @php
                    $colors = ['green', 'blue', 'purple', 'yellow'];
                @endphp

                @foreach ($overviewObats ?? [] as $i => $d)
                    @php
                        $colorIndex = $i % count($colors); // agar tidak error kalau data > jumlah warna
                    @endphp
                    <div
                        class="p-3 bg-white/50 dark:bg-slate-800/50 rounded-lg border border-slate-200/30 dark:border-slate-700/30">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-foreground">{{ $d['nama'] }}</span>
                            <span class="text-sm font-bold text-foreground">{{ $d['stok'] ?? 0 }} pcs</span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-{{ $colors[$colorIndex] }}-500 to-{{ $colors[$colorIndex] }}-600 h-2 rounded-full"
                                style="width: {{ ($totalStokObat ?? 0) > 0 ? (($d['stok'] ?? 0) / $totalStokObat) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <h3 class="text-base sm:text-lg font-semibold text-foreground">Statistik Penjualan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                    class="p-4 sm:p-6 bg-gradient-to-br from-blue-500/10 via-blue-600/5 to-transparent border border-blue-500/20 rounded-xl hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Transaksi</p>
                    <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1">{{ $totalTransaksi ?? 0 }}</p>
                </div>
                <div
                    class="p-4 sm:p-6 bg-gradient-to-br from-green-500/10 via-green-600/5 to-transparent border border-green-500/20 rounded-xl hover:shadow-lg hover:shadow-green-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Produk Terjual</p>
                    <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1">{{ $totalobatterjual ?? 0 }} Pcs </p>
                </div>
                <div
                    class="p-4 sm:p-6 bg-gradient-to-br from-purple-500/10 via-purple-600/5 to-transparent border border-purple-500/20 rounded-xl hover:shadow-lg hover:shadow-purple-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Keuntungan Bulan Ini</p>
                    <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1">
                        {{ formatRupiah($totalKeuntunganBulanIni ?? 0) }}</p>
                </div>
                <div
                    class="p-4 sm:p-6 bg-gradient-to-br from-red-500/10 via-red-600/5 to-transparent border border-red-500/20 rounded-xl hover:shadow-lg hover:shadow-red-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Modal Bulan Ini</p>
                    <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1">{{ formatRupiah($totalModalBulanIni ?? 0) }}
                    </p>
                </div>
                <div
                    class="p-4 sm:p-6 bg-gradient-to-br from-indigo-500/10 via-indigo-600/5 to-transparent border border-indigo-500/20 rounded-xl hover:shadow-lg hover:shadow-indigo-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Penjualan Bulan Ini</p>
                    <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1">
                        {{ formatRupiah($totalPenjualanBulanIni ?? 0) }}</p>
                </div>
                <div
                    class="p-4 sm:p-6 bg-gradient-to-br from-yellow-500/10 via-yellow-600/5 to-transparent border border-yellow-500/20 rounded-xl hover:shadow-lg hover:shadow-yellow-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Keuntungan Hari Ini</p>
                    <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1">
                        {{ formatRupiah($totalKeuntunganHariIni ?? 0) }}</p>
                </div>
                <div
                    class="p-4 sm:p-6 bg-gradient-to-br from-red-500/10 via-red-600/5 to-transparent border border-red-500/20 rounded-xl hover:shadow-lg hover:shadow-red-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Modal Hari Ini</p>
                    <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1">{{ formatRupiah($totalModalHariIni ?? 0) }}
                    </p>
                </div>
                <div
                    class="p-4 sm:p-6 bg-gradient-to-br from-indigo-500/10 via-indigo-600/5 to-transparent border border-indigo-500/20 rounded-xl hover:shadow-lg hover:shadow-indigo-500/10 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                    <p class="text-xs sm:text-sm text-muted-foreground font-medium">Total Penjualan Hari Ini</p>
                    <p class="text-2xl sm:text-3xl font-bold text-foreground mt-1">{{ formatRupiah($penjualanHariIni ?? 0) }}
                    </p>
                </div>
            </div>
        </div>



        <div
            class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-900/50 rounded-xl p-4 sm:p-6 border border-slate-200/50 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h3 class="text-base sm:text-lg font-semibold text-foreground">Transaksi Terbaru</h3>
            </div>
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    @if (count($transaksis ?? []) === 0)
                        <h1 class="my-4 font-medium text-xl text-foreground text-center">Tidak ada data transaksi!</h1>
                    @else
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-700">
                                    <th
                                        class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50 rounded-l-lg">
                                        ID</th>
                                    <th
                                        class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50">
                                        Obat</th>
                                    <th
                                        class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50">
                                        Status</th>
                                    <th
                                        class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50 hidden sm:table-cell">
                                        Jumlah</th>
                                    <th
                                        class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50">
                                        Total</th>
                                    <th
                                        class="text-left py-3 px-2 text-xs sm:text-sm font-semibold text-muted-foreground bg-slate-100/50 dark:bg-slate-800/50 rounded-r-lg hidden md:table-cell">
                                        Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksis ?? [] as $transaksi)
                                    <tr
                                        class="border-b border-slate-200/50 dark:border-slate-700/50 hover:bg-white/50 dark:hover:bg-slate-800/30 transition-colors duration-200 active:bg-slate-100/50 dark:active:bg-slate-700/50">
                                        <td class="py-3 sm:py-4 px-2">
                                            <span
                                                class="text-xs sm:text-sm font-mono font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded">#{{ $transaksi->id }}</span>
                                        </td>
                                        <td class="py-3 sm:py-4 px-2">
                                            <div class="flex items-center">
                                                <div class="min-w-0">
                                                    <span
                                                        class="text-xs sm:text-sm font-medium text-foreground block truncate">{{ $transaksi->obat->nama ?? '-' }}</span>
                                                    <span
                                                        class="text-xs text-muted-foreground sm:hidden">{{ $transaksi->qty }}
                                                        unit</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 sm:py-4 px-2">
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium
        @switch($transaksi->status)
            @case('SUCCESS') bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400 @break
            @case('RETURN') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400 @break
            @case('VOID') bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400 @break
        @endswitch
    ">
                                                {{ $transaksi->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 sm:py-4 px-2 hidden sm:table-cell">
                                            <span
                                                class="text-sm text-foreground bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-full">{{ $transaksi->qty }}
                                                unit</span>
                                        </td>
                                            <td class="py-3 sm:py-4 px-2">
                                                @if ($transaksi->status === 'VOID')
                                                    <span class="text-xs sm:text-sm italic text-gray-400">VOID</span>
                                                @else
                                                    @php
                                                        $netTotal = 0;
                                                        foreach ($transaksi->items ?? [] as $item) {
                                                            $returnedQty = $item->returned_qty ?? 0;
                                                            $netQty = max($item->qty - $returnedQty, 0);
                                                            $netTotal += $item->harga_jual * $netQty;
                                                        }
                                                    @endphp

                                                    <span
                                                        class="text-xs sm:text-sm font-semibold text-green-600 dark:text-green-400">
                                                        {{ formatRupiah($netTotal) }}
                                                    </span>

                                                    @if ($transaksi->status === 'RETURN')
                                                        <div class="text-[10px] text-yellow-600 dark:text-yellow-400">
                                                            setelah return
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="py-3 sm:py-4 px-2 hidden md:table-cell">
                                            <span
                                                class="text-sm text-muted-foreground">{{ timeAgo($transaksi->transaction->created_at ?? null) }}</span>
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

    <script>
        const totalPenjualanLabels = @json($totalPenjualanLabels ?? []).reverse();
        const totalPenjualanTotals = @json($totalPenjualanTotals ?? []).reverse();

        const totalModalLabels = @json($totalModalLabels ?? []).reverse();
        const totalModalTotals = @json($totalModalTotals ?? []).reverse();

        const totalKeuntunganLabels = @json($totalKeuntunganLabels ?? []).reverse();
        const totalKeuntunganTotals = @json($totalKeuntunganTotals ?? []).reverse();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin/dashboard/chart.js') }}"></script>
@endsection
