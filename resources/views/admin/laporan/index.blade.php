@extends('admin.layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-6 space-y-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                Laporan Transaksi
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Ringkasan penjualan, keuntungan, dan status transaksi
            </p>
        </div>

        {{-- FILTER --}}
        <div
            class="bg-white dark:bg-neutral-900 rounded-xl shadow-sm border 
                border-gray-200 dark:border-neutral-800 p-4">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400">Dari</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="mt-1 w-full rounded-lg border 
                           border-gray-300 dark:border-neutral-700
                           bg-white dark:bg-neutral-950
                           text-gray-800 dark:text-gray-100
                           px-3 py-2 focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400">Sampai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="mt-1 w-full rounded-lg border 
                           border-gray-300 dark:border-neutral-700
                           bg-white dark:bg-neutral-950
                           text-gray-800 dark:text-gray-100
                           px-3 py-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-gray-400">Status</label>
                    <select name="status"
                        class="mt-1 rounded-lg border px-3 py-2
               bg-white dark:bg-neutral-950
               border-gray-300 dark:border-neutral-700
               text-gray-800 dark:text-gray-100">
                        <option value="">Semua</option>
                        <option value="SUCCESS" {{ request('status') == 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
                        <option value="VOID" {{ request('status') == 'VOID' ? 'selected' : '' }}>VOID</option>
                        <option value="RETURN" {{ request('status') == 'RETURN' ? 'selected' : '' }}>RETURN</option>
                    </select>
                </div>

                <button
                    class="h-10 px-6 rounded-lg bg-primary text-white
                       hover:bg-primary/90 transition">
                    Filter
                </button>
            </form>
        </div>

        {{-- SUMMARY --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $stats = [
                    ['label' => 'Total Transaksi', 'value' => $totalTransaksi],
                    ['label' => 'SUCCESS', 'value' => $totalSuccess],
                    ['label' => 'BATAL', 'value' => $totalVoid],
                    ['label' => 'RETURN', 'value' => $totalReturn],
                ];
            @endphp

            @foreach ($stats as $s)
                <div
                    class="bg-white dark:bg-neutral-900 rounded-xl shadow-sm p-4
                    border border-gray-200 dark:border-neutral-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $s['label'] }}</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mt-1">
                        {{ $s['value'] }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- FINANCIAL --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-white dark:bg-neutral-900 rounded-xl shadow-sm p-4
                border border-gray-200 dark:border-neutral-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Jual</p>
                <h3 class="text-xl font-semibold mt-1 text-gray-800 dark:text-gray-100">
                    Rp {{ number_format($totalJual) }}
                </h3>
            </div>

            <div
                class="bg-white dark:bg-neutral-900 rounded-xl shadow-sm p-4
                border border-gray-200 dark:border-neutral-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Modal</p>
                <h3 class="text-xl font-semibold mt-1 text-gray-800 dark:text-gray-100">
                    Rp {{ number_format($totalModal) }}
                </h3>
            </div>

            <div
                class="bg-white dark:bg-neutral-900 rounded-xl shadow-sm p-4
                border border-gray-200 dark:border-neutral-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Keuntungan</p>
                <h3 class="text-xl font-semibold mt-1 text-green-600 dark:text-green-400">
                    Rp {{ number_format($keuntungan) }}
                </h3>
            </div>

            <div
                class="bg-white dark:bg-neutral-900 rounded-xl shadow-sm p-4
                border border-gray-200 dark:border-neutral-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Margin</p>
                <h3 class="text-xl font-semibold mt-1 text-indigo-600 dark:text-indigo-400">
                    {{ $margin }}%
                </h3>
            </div>
        </div>

        <div
            class="bg-white dark:bg-neutral-900 rounded-xl shadow-sm
            border border-gray-200 dark:border-neutral-800">

            <div class="overflow-x-auto max-h-[520px]">
                <table class="min-w-full text-sm">
                    <thead
                        class="sticky top-0 z-10
                         bg-gray-50 dark:bg-neutral-800
                         text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">Kode</th>
                            <th class="px-4 py-3 text-center">Tanggal</th>
                            <th class="px-4 py-3 text-center">Kasir</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-800">
                        @forelse($transaksis as $trx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition">
                                <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">
                                    {{ $trx->kode }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">
                                    {{ $trx->created_at }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">
                                    {{ $trx->user->nama ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium
    @switch($trx->status)
        @case('SUCCESS')
            bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400
            @break
        @case('VOID')
            bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400
            @break
        @case('RETURN')
            bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400
            @break
    @endswitch
">
                                        {{ $trx->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-800 dark:text-gray-100">
                                    @if ($trx->status === 'VOID')
                                        <span class="italic text-gray-400">BATAL</span>
                                    @else
                                        @php
                                            $netTotal = 0;
                                            foreach ($trx->items as $item) {
                                                $returnedQty = $item->returned_qty ?? 0;
                                                $netQty = max($item->qty - $returnedQty, 0);
                                                $netTotal += $item->harga_jual * $netQty;
                                            }
                                        @endphp
                                        Rp {{ number_format($netTotal) }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-neutral-800">
                {{ $transaksis->withQueryString()->links() }}
            </div>
        </div>


        <div class="p-4 border-t border-gray-200 dark:border-neutral-800">
            {{ $transaksis->links() }}
        </div>
    </div>

    </div>
@endsection
