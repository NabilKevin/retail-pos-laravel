<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Transaksi\ReturnRequest;
use App\Http\Requests\Admin\Transaksi\VoidRequest;
use App\Models\Transaction;
use App\Services\ExportService;
use App\Services\TransaksiService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    public function __construct(
        private readonly TransaksiService $transaksiService,
        private readonly ExportService $exportService,
    ) {}

    public function index(Request $request): View
    {
        $search    = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $status    = $request->input('status');

        $query = Transaction::with(['items.obat', 'returns.item.obat', 'returns.user', 'voidBy'])
            ->when($search, fn($q) => $q->where('kode', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status));

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ]);
        }

        $transaksis = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.transaksi.index', compact('transaksis', 'search', 'startDate', 'endDate', 'status'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate   = $request->end_date   ? Carbon::parse($request->end_date)->endOfDay()   : null;

        $query = Transaction::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $this->exportService->exportTransaksi($query, includeStatusColumn: true);
    }

    public function cetakStruk(string $kode): View
    {
        $transaction = $this->transaksiService->loadStruk($kode);

        return view('admin.cetak.struk', compact('transaction'));
    }

    public function profit(Request $request): JsonResponse
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate   = $request->end_date   ? Carbon::parse($request->end_date)->endOfDay()   : null;

        $query = Transaction::with('items')
            ->whereIn('status', ['SUCCESS', 'RETURN']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->search) {
            $query->where('kode', 'like', "%{$request->search}%");
        }

        $totalModal = 0;
        $totalJual  = 0;

        foreach ($query->get() as $transaction) {
            foreach ($transaction->items as $item) {
                $netQty = $item->qty - ($item->returned_qty ?? 0);
                if ($netQty <= 0) {
                    continue;
                }
                $totalModal += $item->harga_modal * $netQty;
                $totalJual  += $item->harga_jual  * $netQty;
            }
        }

        $keuntungan = $totalJual - $totalModal;
        $margin     = $totalJual > 0 ? round(($keuntungan / $totalJual) * 100, 2) : 0;

        return response()->json([
            'total_jual'  => $totalJual,
            'total_modal' => $totalModal,
            'keuntungan'  => $keuntungan,
            'margin'      => $margin,
        ]);
    }

    public function void(VoidRequest $request, int $id): RedirectResponse
    {
        try {
            $this->transaksiService->processVoid($id, $request->validated('void_reason'));

            return redirect()->back()->with('success', 'Transaksi berhasil di-VOID');
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function processReturn(ReturnRequest $request, int $transaction): RedirectResponse
    {
        try {
            $this->transaksiService->processReturn(
                $transaction,
                $request->validated('items'),
                $request->validated('reason')
            );

            return redirect()->back()->with('success', 'Return multi item berhasil diproses');
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
