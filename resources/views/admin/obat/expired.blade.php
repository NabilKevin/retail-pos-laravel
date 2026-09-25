@extends('admin.layouts.app')

@section('page-title', 'Barang Expired')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 mt-6 space-y-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Barang Expired
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Total:
                    <span id="totalExpired" class="font-semibold text-red-600">
                        {{ $obats->count() }}
                    </span>
                    obat expired
                </p>
            </div>

            {{-- Filter & Search --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <input type="date" id="filterDate"
                    class="border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200 dark:bg-gray-800 dark:border-gray-700">

                <select id="sortExpired"
                    class="border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200 dark:bg-gray-800 dark:border-gray-700">
                    <option value="">Urutkan tanggal</option>
                    <option value="asc">Terlama → Terbaru</option>
                    <option value="desc">Terbaru → Terlama</option>
                </select>

                <input type="text" id="searchInput" placeholder="Cari nama obat..."
                    class="border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200 dark:bg-gray-800 dark:border-gray-700">
            </div>

            <a href="{{ route('admin.obat.expired.export') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                Export Excel
            </a>
        </div>

        {{-- Card List --}}
        <div id="expiredList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            @forelse($obats as $obat)
                <div class="expired-card bg-white dark:bg-gray-900 border rounded-xl p-4 shadow-sm hover:shadow-md transition"
                    data-nama="{{ strtolower($obat->nama) }}"
                    data-expired="{{ \Carbon\Carbon::parse($obat->expired_at)->format('Y-m-d') }}">
                    @php
                        $hari = \Carbon\Carbon::now()->diffInDays($obat->expired_at, false);
                    @endphp
                    <div class="flex items-start justify-between">
                        <h3 class="font-semibold text-gray-800 dark:text-white">
                            {{ $obat->nama }}
                        </h3>
                        @if ($hari < 0)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-600 text-white">
                                Expired
                            </span>
                        @elseif ($hari <= 7)
                            <span class="px-2 py-1 text-xs rounded-full bg-orange-500 text-white">
                                H-7
                            </span>
                        @elseif ($hari <= 30)
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-400 text-gray-900">
                                H-30
                            </span>
                        @endif
                    </div>

                    <div class="mt-3 text-sm space-y-1">
                        <p>
                            <span class="text-gray-500">Stok:</span>
                            <span class="font-semibold">{{ $obat->stok }}</span>
                        </p>
                        <p>
                            <span class="text-gray-500">Tanggal Expired:</span>
                            <span class="font-semibold text-red-600">
                                {{ \Carbon\Carbon::parse($obat->expired_at)->format('d/m/Y') }}
                            </span>
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-12">
                    🎉 Tidak ada barang expired
                </div>
            @endforelse

        </div>
    </div>

    {{-- SCRIPT FRONTEND --}}
    <script>
        const searchInput = document.getElementById('searchInput');
        const filterDate = document.getElementById('filterDate');
        const sortExpired = document.getElementById('sortExpired');
        const expiredList = document.getElementById('expiredList');
        const totalExpired = document.getElementById('totalExpired');

        function filterAndSort() {
            const keyword = searchInput.value.toLowerCase();
            const date = filterDate.value;
            const sort = sortExpired.value;

            let cards = Array.from(document.querySelectorAll('.expired-card'));

            // FILTER
            cards.forEach(card => {
                const nama = card.dataset.nama;
                const expired = card.dataset.expired;

                const matchNama = nama.includes(keyword);
                const matchDate = !date || expired === date;

                if (matchNama && matchDate) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });

            // SORT (HANYA YANG TAMPIL)
            let visibleCards = cards.filter(card => !card.classList.contains('hidden'));

            if (sort) {
                visibleCards.sort((a, b) => {
                    const dateA = new Date(a.dataset.expired);
                    const dateB = new Date(b.dataset.expired);
                    return sort === 'asc' ?
                        dateA - dateB :
                        dateB - dateA;
                });

                visibleCards.forEach(card => expiredList.appendChild(card));
            }

            totalExpired.textContent = visibleCards.length;
        }

        searchInput.addEventListener('input', filterAndSort);
        filterDate.addEventListener('change', filterAndSort);
        sortExpired.addEventListener('change', filterAndSort);
    </script>

@endsection
