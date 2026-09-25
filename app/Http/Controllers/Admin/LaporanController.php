<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function laporan(Request $request): View
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : null;

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : null;

        $transactionQuery = Transaction::query();

        if ($startDate && $endDate) {
            $transactionQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->status) {
            $transactionQuery->where('status', $request->status);
        }

        // --- Status counts ---
        $totalTransaksi = (clone $transactionQuery)->count();
        $totalSuccess   = (clone $transactionQuery)->where('status', 'SUCCESS')->count();
        $totalVoid      = (clone $transactionQuery)->where('status', 'VOID')->count();
        $totalReturn    = (clone $transactionQuery)->where('status', 'RETURN')->count();

        // --- Financial aggregates (SUCCESS + RETURN only) ---
        $transactions = (clone $transactionQuery)
            ->whereIn('status', ['SUCCESS', 'RETURN'])
            ->with('items')
            ->get();

        $totalJual  = 0;
        $totalModal = 0;

        foreach ($transactions as $trx) {
            foreach ($trx->items as $item) {
                $netQty = max($item->qty - ($item->returned_qty ?? 0), 0);
                if ($netQty === 0) {
                    continue;
                }
                $totalJual  += $item->harga_jual  * $netQty;
                $totalModal += $item->harga_modal * $netQty;
            }
        }

        $keuntungan = $totalJual - $totalModal;
        $margin     = $totalJual > 0 ? round(($keuntungan / $totalJual) * 100, 2) : 0;

        // --- Paginated transaction table ---
        $transaksis = (clone $transactionQuery)
            ->with(['user', 'items'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.laporan.index', compact(
            'totalTransaksi',
            'totalSuccess',
            'totalVoid',
            'totalReturn',
            'totalJual',
            'totalModal',
            'keuntungan',
            'margin',
            'transaksis',
        ));
    }
}
