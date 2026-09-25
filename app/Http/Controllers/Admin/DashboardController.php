<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\TransactionItem;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService) {}

    public function index(): View
    {
        $today = Carbon::today();

        // --- Obat stats (use scopes for readability) ---
        $totalObat        = Obat::count();
        $totalStokMenipis = Obat::lowStock()->count();
        $totalUser        = \App\Models\User::count();

        $totalExpired   = Obat::expired()->count();
        $totalExpiredH7 = Obat::expiringSoon(7)->count();
        $totalExpiredH30 = Obat::expiringSoon(30)->where('expired_at', '>', $today->addDays(7))->count();

        // --- Transaction item base query ---
        // NOTE: Do NOT add orderBy here — it is inherited by every clone() inside
        // DashboardService. GROUP BY chart queries use reorder() to strip it, but
        // having it here causes SQLite errors. The recent-items slice handles its own ordering.
        $baseQuery = TransactionItem::select('transactionitem.*')
            ->join('transaction', 'transactionitem.transaction_id', '=', 'transaction.id')
            ->where('transaction.status', '!=', 'VOID')
            ->with(['obat', 'transaction']);

        $dataTransaksi = $this->dashboardService->getTransaksiData($baseQuery);
        $dataChart     = $this->dashboardService->getChartData($baseQuery);
        $dataSummary   = $this->dashboardService->getSummaryData();

        return view('admin.dashboard.index', [
            'totalObat'        => $totalObat,
            'totalStokMenipis' => $totalStokMenipis,
            'totalUser'        => $totalUser,

            'totalExpired'   => $totalExpired,
            'totalExpiredH7' => $totalExpiredH7,
            'totalExpiredH30' => $totalExpiredH30,

            'totalKenaikanObat'  => $dataSummary['kenaikanObat'],
            'totalKenaikanUser'  => $dataSummary['kenaikanUser'],
            'overviewObats'      => $dataSummary['listStokObat'],
            'totalStokObat'      => $dataSummary['totalStokObat'],

            'transaksis'              => $dataTransaksi['transaksi'],
            'penjualanHariIni'        => $dataTransaksi['penjualanHariIni'],
            'totalKenaikanPenjualan'  => $dataTransaksi['kenaikanPenjualan'],
            'totalobatterjual'        => $dataTransaksi['totalObatTerjual'],
            'totalModal'              => $dataTransaksi['totalModal'],
            'totalPenjualan'          => $dataTransaksi['totalPenjualan'],
            'totalKeuntungan'         => $dataTransaksi['totalKeuntungan'],
            'totalModalBulanIni'      => $dataTransaksi['totalModalBulanIni'],
            'totalPenjualanBulanIni'  => $dataTransaksi['totalPenjualanBulanIni'],
            'totalKeuntunganBulanIni' => $dataTransaksi['totalKeuntunganBulanIni'],
            'totalModalBulanLalu'     => $dataTransaksi['totalModalBulanLalu'],
            'totalPenjualanBulanLalu' => $dataTransaksi['totalPenjualanBulanLalu'],
            'totalKeuntunganBulanLalu' => $dataTransaksi['totalKeuntunganBulanLalu'],
            'totalModalperobat'       => $dataTransaksi['totalModalPerObat'],
            'totalTransaksi'          => $dataTransaksi['totalTransaksi'],
            'days'                    => $dataTransaksi['days'],
            'totalKeuntunganHariIni'  => $dataTransaksi['totalKeuntunganHariIni'],
            'totalModalHariIni'       => $dataTransaksi['totalModalHariIni'],
            'totalPenjualanHariIni'   => $dataTransaksi['penjualanHariIni'],

            'totalPenjualanLabels'   => $dataChart['penjualanLabels'],
            'totalPenjualanTotals'   => $dataChart['penjualanTotals'],
            'totalModalLabels'       => $dataChart['modalLabels'],
            'totalModalTotals'       => $dataChart['modalTotals'],
            'totalKeuntunganLabels'  => $dataChart['keuntunganLabels'],
            'totalKeuntunganTotals'  => $dataChart['keuntunganTotals'],
        ]);
    }
}
