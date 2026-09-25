<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today     = Carbon::today();
        $yesterday = Carbon::yesterday();

        $baseQuery = TransactionItem::select('transactionitem.*')
            ->join('transaction', 'transactionitem.transaction_id', '=', 'transaction.id')
            ->where('transaction.status', '!=', 'VOID')
            ->orderBy('transaction.created_at', 'desc')
            ->with(['obat', 'transaction']);

        $recentTransaksi = (clone $baseQuery)->take(3)->get();

        $penjualanHariIni = (clone $baseQuery)
            ->whereDate('transaction.created_at', $today)
            ->sum('transactionitem.subtotal');

        $penjualanKemarin = (clone $baseQuery)
            ->whereDate('transaction.created_at', $yesterday)
            ->sum('transactionitem.subtotal');

        $totalKenaikanPenjualan = $this->growthPercent($penjualanHariIni, $penjualanKemarin);

        $totalTransaksiHariIni = Transaction::whereDate('created_at', $today)->count();
        $totalTransaksiKemarin = Transaction::whereDate('created_at', $yesterday)->count();
        $totalKenaikanTransaksi = $this->growthPercent($totalTransaksiHariIni, $totalTransaksiKemarin);

        $totalItemTerjualHariIni = (clone $baseQuery)
            ->whereDate('transaction.created_at', $today)
            ->count();

        $totalItemTerjualKemarin = (clone $baseQuery)
            ->whereDate('transaction.created_at', $yesterday)
            ->count();

        $totalKenaikanItemTerjual = $this->growthPercent($totalItemTerjualHariIni, $totalItemTerjualKemarin);

        return view('kasir.dashboard.index', [
            'transaksis'               => $recentTransaksi,
            'penjualanHariIni'         => $penjualanHariIni,
            'totalKenaikanPenjualan'   => $totalKenaikanPenjualan,
            'totalTransaksi'           => $totalTransaksiHariIni,
            'totalKenaikanTransaksi'   => $totalKenaikanTransaksi,
            'totalItemTerjual'         => $totalItemTerjualHariIni,
            'totalKenaikanItemTerjual' => $totalKenaikanItemTerjual,
        ]);
    }

    private function growthPercent(int|float $current, int|float $previous): int
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return (int) ceil(($current - $previous) / $previous * 100);
    }
}
