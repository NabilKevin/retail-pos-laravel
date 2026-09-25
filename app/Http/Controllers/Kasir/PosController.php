<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kasir\Pos\StoreRequest;
use App\Models\Obat;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\TransaksiService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends Controller
{
    public function __construct(private readonly TransaksiService $transaksiService) {}

    public function index(): View
    {
        $obats = Obat::select(['id', 'kode_barcode', 'nama', 'harga_jual as harga', 'stok', 'expired_at'])->get();

        return view('kasir.pos.index', compact('obats'));
    }

    public function bayar(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Lock all obat rows first to prevent race conditions
            foreach ($data['cart'] as $item) {
                $obat = Obat::where('id', $item['id'])->lockForUpdate()->first();

                if (! $obat) {
                    throw new \RuntimeException('Obat tidak ditemukan.');
                }

                if ($obat->expired_at && Carbon::parse($obat->expired_at)->isPast()) {
                    throw new \RuntimeException("Obat {$obat->nama} sudah kadaluarsa.");
                }

                if ($obat->stok <= 0) {
                    throw new \RuntimeException("Stok {$obat->nama} habis.");
                }

                if ($obat->stok < $item['qty']) {
                    throw new \RuntimeException("Stok {$obat->nama} tidak mencukupi.");
                }
            }

            // Generate unique transaction code
            $lastId = Transaction::max('id') + 1;
            $kode   = 'TRX-' . date('Ymd') . '-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);

            $transaksi = Transaction::create([
                'kode'            => $kode,
                'total_transaksi' => $data['totalTransaction'],
                'total_dibayar'   => $data['totalPaid'],
                'total_kembalian' => $data['totalChange'],
                'status'          => 'SUCCESS',
                'user_id'         => Auth::id(),
                'created_at'      => now()->timezone('Asia/Jakarta'),
                'updated_at'      => now()->timezone('Asia/Jakarta'),
                'paid_at'         => now()->timezone('Asia/Jakarta'),
            ]);

            // Save items and deduct stock
            foreach ($data['cart'] as $item) {
                $obat = Obat::where('id', $item['id'])->lockForUpdate()->first();
                $obat->decrement('stok', $item['qty']);

                TransactionItem::create([
                    'obat_id'        => $obat->id,
                    'transaction_id' => $transaksi->id,
                    'harga_modal'    => $obat->harga_modal,
                    'harga_jual'     => $obat->harga_jual,
                    'qty'            => $item['qty'],
                    'subtotal'       => $obat->harga_jual * $item['qty'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status'       => 'success',
                'message'      => 'Transaksi berhasil',
                'redirect_url' => route('kasir.cetak.struk', $transaksi->kode),
            ]);
        } catch (\RuntimeException $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function cetakStruk(string $kode): View
    {
        $transaction = $this->transaksiService->loadStruk($kode);

        return view('kasir.cetak.struk', compact('transaction'));
    }
}
