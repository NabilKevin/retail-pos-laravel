<?php

namespace App\Services;

use App\Models\Obat;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Returns the database-agnostic SQL expression for formatting a datetime column
     * to 'YYYY-MM'. Supports SQLite and MySQL automatically.
     */
    private function monthFormatExpr(string $column): string
    {
        return match (DB::getDriverName()) {
            'sqlite' => "strftime('%Y-%m', {$column})",
            default  => "DATE_FORMAT({$column}, '%Y-%m')",
        };
    }

    /**
     * Build chart data for the last 6 months (penjualan, modal, keuntungan).
     *
     * @param  Builder $baseQuery  A pre-configured TransactionItem query builder (cloned internally)
     * @return array{
     *     penjualanLabels: \Illuminate\Support\Collection,
     *     penjualanTotals: \Illuminate\Support\Collection,
     *     modalLabels: \Illuminate\Support\Collection,
     *     modalTotals: \Illuminate\Support\Collection,
     *     keuntunganLabels: \Illuminate\Support\Collection,
     *     keuntunganTotals: \Illuminate\Support\Collection,
     * }
     */
    public function getChartData(Builder $baseQuery): array
    {
        $sixMonthsAgo    = Carbon::now()->subMonths(6)->startOfMonth();
        $monthFormatExpr = $this->monthFormatExpr('"transaction"."created_at"');

        // Build a dedicated sub-query WITHOUT the outer orderBy(created_at) that
        // conflicts with GROUP BY in SQLite. The clone carries any pre-set joins/wheres,
        // but we strip ordering and add our own.
        $makeQuery = function () use ($baseQuery, $monthFormatExpr, $sixMonthsAgo) {
            return (clone $baseQuery)
                ->reorder() // Remove the orderBy('transaction.created_at') from baseQuery
                ->where('transaction.created_at', '>=', $sixMonthsAgo->toDateTimeString())
                ->groupBy(DB::raw($monthFormatExpr))
                ->orderBy(DB::raw($monthFormatExpr), 'asc');
        };

        $penjualan = $makeQuery()
            ->select(
                DB::raw("{$monthFormatExpr} as bulan"),
                DB::raw('SUM("transactionitem"."subtotal") as total')
            )
            ->get();

        $modal = $makeQuery()
            ->select(
                DB::raw("{$monthFormatExpr} as bulan"),
                DB::raw('SUM("transactionitem"."harga_modal" * "transactionitem"."qty") AS total')
            )
            ->get();

        $keuntungan = $makeQuery()
            ->select(
                DB::raw("{$monthFormatExpr} as bulan"),
                DB::raw('SUM(("transactionitem"."harga_jual" - "transactionitem"."harga_modal") * "transactionitem"."qty") AS total')
            )
            ->get();

        return [
            'penjualanLabels'  => $penjualan->pluck('bulan'),
            'penjualanTotals'  => $penjualan->pluck('total'),
            'modalLabels'      => $modal->pluck('bulan'),
            'modalTotals'      => $modal->pluck('total'),
            'keuntunganLabels' => $keuntungan->pluck('bulan'),
            'keuntunganTotals' => $keuntungan->pluck('total'),
        ];
    }

    /**
     * Build summary statistics for obat and user growth (month-over-month).
     *
     * @return array{
     *     kenaikanObat: int,
     *     kenaikanUser: int,
     *     listStokObat: \Illuminate\Support\Collection,
     *     totalStokObat: int,
     * }
     */
    public function getSummaryData(): array
    {
        $startLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endLastMonth   = Carbon::now()->subMonth()->endOfMonth();
        $startThisMonth = Carbon::now()->startOfMonth();
        $endThisMonth   = Carbon::now()->endOfMonth();

        $totalObatLastMonth = Obat::whereBetween('created_at', [$startLastMonth, $endLastMonth])->count();
        $totalObatThisMonth = Obat::whereBetween('created_at', [$startThisMonth, $endThisMonth])->count();
        $kenaikanObat = $totalObatLastMonth > 0
            ? (int) ceil(($totalObatThisMonth - $totalObatLastMonth) / $totalObatLastMonth * 100)
            : ($totalObatThisMonth > 0 ? 100 : 0);

        $totalUserLastMonth = User::whereBetween('created_at', [$startLastMonth, $endLastMonth])->count();
        $totalUserThisMonth = User::whereBetween('created_at', [$startThisMonth, $endThisMonth])->count();
        $kenaikanUser = max(0, $totalUserThisMonth - $totalUserLastMonth);

        // Top 4 obat by lowest stock (for overview widget)
        $allObats      = Obat::select(['nama', 'stok'])->get();
        $totalStokObat = $allObats->sum('stok');
        $listStokObat  = $allObats->sortBy('stok')->take(4);

        return [
            'kenaikanObat'  => $kenaikanObat,
            'kenaikanUser'  => $kenaikanUser,
            'listStokObat'  => $listStokObat,
            'totalStokObat' => $totalStokObat,
        ];
    }

    /**
     * Build full transaction statistics (today, this month, last month, totals).
     *
     * All aggregates use Eloquent's whereDate/whereBetween/sum which produce
     * database-agnostic SQL and correct parameter binding.
     *
     * @param  Builder $baseQuery  A pre-configured TransactionItem query builder (cloned internally)
     * @return array<string, mixed>
     */
    public function getTransaksiData(Builder $baseQuery): array
    {
        $today     = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        $startThisMonth = Carbon::now()->startOfMonth()->toDateTimeString();
        $endThisMonth   = Carbon::now()->endOfMonth()->toDateTimeString();
        $startLastMonth = Carbon::now()->subMonth()->startOfMonth()->toDateTimeString();
        $endLastMonth   = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();

        // Aggregate column expressions (safe for both MySQL and SQLite)
        $modalExpr = DB::raw('"transactionitem"."harga_modal" * "transactionitem"."qty"');

        // --- Scalar aggregates (DB-level, parameter-bound) ---
        $penjualanHariIni = (clone $baseQuery)
            ->whereDate('transaction.created_at', $today)
            ->sum('transactionitem.subtotal');

        $penjualanKemarin = (clone $baseQuery)
            ->whereDate('transaction.created_at', $yesterday)
            ->sum('transactionitem.subtotal');

        $totalObatTerjual = (clone $baseQuery)->sum('transactionitem.qty');

        $totalModal     = (clone $baseQuery)->sum($modalExpr);
        $totalPenjualan = (clone $baseQuery)->sum('transactionitem.subtotal');
        $totalKeuntungan = $totalPenjualan - $totalModal;

        $totalModalHariIni = (clone $baseQuery)
            ->whereDate('transaction.created_at', $today)
            ->sum($modalExpr);

        $totalModalBulanIni = (clone $baseQuery)
            ->whereBetween('transaction.created_at', [$startThisMonth, $endThisMonth])
            ->sum($modalExpr);

        $totalPenjualanBulanIni = (clone $baseQuery)
            ->whereBetween('transaction.created_at', [$startThisMonth, $endThisMonth])
            ->sum('transactionitem.subtotal');

        $totalKeuntunganBulanIni = $totalPenjualanBulanIni - $totalModalBulanIni;

        $totalModalBulanLalu = (clone $baseQuery)
            ->whereBetween('transaction.created_at', [$startLastMonth, $endLastMonth])
            ->sum($modalExpr);

        $totalPenjualanBulanLalu = (clone $baseQuery)
            ->whereBetween('transaction.created_at', [$startLastMonth, $endLastMonth])
            ->sum('transactionitem.subtotal');

        $totalKeuntunganBulanLalu = $totalPenjualanBulanLalu - $totalModalBulanLalu;

        $totalKeuntunganHariIni = $penjualanHariIni - $totalModalHariIni;

        // Per-obat modal breakdown (for detailed table).
        // Uses reorder() to strip the inherited orderBy which conflicts with GROUP BY in SQLite.
        $totalModalPerObat = (clone $baseQuery)
            ->reorder()
            ->select(
                'transactionitem.obat_id',
                DB::raw('SUM("transactionitem"."harga_modal" * "transactionitem"."qty") as total_modal_per_obat'),
                DB::raw('SUM("transactionitem"."subtotal") as total_penjualan_per_obat')
            )
            ->groupBy('transactionitem.obat_id')
            ->with('obat')
            ->get();

        // Sales growth percentage
        $kenaikanPenjualan = $this->calculateGrowthPercentage($penjualanHariIni, $penjualanKemarin);

        // Recent 3 transaction items
        $recentTransaksi = (clone $baseQuery)->take(3)->get();

        $totalTransaksi = (clone $baseQuery)->count();
        $days = min($totalTransaksi, 7);

        return [
            'transaksi'                => $recentTransaksi,
            'penjualanHariIni'         => $penjualanHariIni,
            'kenaikanPenjualan'        => $kenaikanPenjualan,
            'totalObatTerjual'         => $totalObatTerjual,
            'totalModal'               => $totalModal,
            'totalPenjualan'           => $totalPenjualan,
            'totalKeuntungan'          => $totalKeuntungan,
            'totalModalBulanIni'       => $totalModalBulanIni,
            'totalPenjualanBulanIni'   => $totalPenjualanBulanIni,
            'totalKeuntunganBulanIni'  => $totalKeuntunganBulanIni,
            'totalModalBulanLalu'      => $totalModalBulanLalu,
            'totalPenjualanBulanLalu'  => $totalPenjualanBulanLalu,
            'totalKeuntunganBulanLalu' => $totalKeuntunganBulanLalu,
            'totalModalPerObat'        => $totalModalPerObat,
            'totalTransaksi'           => $totalTransaksi,
            'days'                     => $days,
            'totalKeuntunganHariIni'   => $totalKeuntunganHariIni,
            'totalModalHariIni'        => $totalModalHariIni,
        ];
    }

    /**
     * Calculate percentage growth between two values.
     */
    private function calculateGrowthPercentage(float|int $current, float|int $previous): int
    {
        if ($previous == 0 && $current > 0) {
            return 100;
        }

        if ($previous == 0) {
            return 0;
        }

        return (int) ceil(($current - $previous) / $previous * 100);
    }
}
