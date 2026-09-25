<?php

namespace App\Services;

use App\Models\Obat;
use App\Models\Transaction;
use App\Models\TransactionReturn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiService
{
    /**
     * Load a transaction by kode and compute return/final values for receipt printing.
     * Used by both Admin and Kasir struk views.
     */
    public function loadStruk(string $kode): Transaction
    {
        $transaction = Transaction::with([
            'items.obat',
            'user',
            'returns',
        ])->where('kode', $kode)->firstOrFail();

        // Build a map: transaction_item_id => total returned qty
        $returnedQtyMap = $transaction->returns
            ->groupBy('transaction_item_id')
            ->map(fn($group) => $group->sum('qty'));

        $totalReturn = $transaction->returns->sum('amount');

        // Annotate each item with computed return values (no DB write)
        foreach ($transaction->items as $item) {
            $returnedQty = $returnedQtyMap[$item->id] ?? 0;

            $item->qty_return       = $returnedQty;
            $item->qty_final        = $item->qty - $returnedQty;
            $item->subtotal_final   = $item->qty_final * $item->harga_jual;
            $item->subtotal_return  = $returnedQty * $item->harga_jual;
        }

        $transaction->total_return    = $totalReturn;
        $transaction->total_final     = $transaction->total_transaksi - $totalReturn;
        $transaction->paid_final      = $transaction->total_dibayar;
        $transaction->total_kembalian = $transaction->paid_final - $transaction->total_final;

        return $transaction;
    }

    /**
     * Process a multi-item return. Restores stock, records return rows, updates transaction status.
     *
     * @param  int                $transactionId
     * @param  array<int, int>    $items   [transaction_item_id => qty_to_return]
     * @param  string             $reason
     * @return void
     *
     * @throws \Throwable
     */
    public function processReturn(int $transactionId, array $items, string $reason): void
    {
        DB::transaction(function () use ($transactionId, $items, $reason): void {
            /** @var object $transaction */
            $transaction = DB::table('transaction')
                ->where('id', $transactionId)
                ->lockForUpdate()
                ->first();

            if (! $transaction || $transaction->status === 'VOID') {
                throw new \RuntimeException('Transaksi tidak valid atau sudah dibatalkan.');
            }

            $totalReturn = 0;

            foreach ($items as $itemId => $qty) {
                $qty = (int) $qty;
                if ($qty <= 0) {
                    continue;
                }

                /** @var object $item */
                $item = DB::table('transactionitem')
                    ->where('id', $itemId)
                    ->where('transaction_id', $transactionId)
                    ->lockForUpdate()
                    ->first();

                if (! $item) {
                    continue;
                }

                $availableQty = $item->qty - $item->returned_qty;
                if ($qty > $availableQty) {
                    throw new \RuntimeException("Qty return melebihi jumlah tersedia untuk item #{$itemId}.");
                }

                $returnAmount  = $item->harga_jual * $qty;
                $totalReturn  += $returnAmount;

                // 1. Record the return
                DB::table('transaction_returns')->insert([
                    'transaction_id'      => $transactionId,
                    'transaction_item_id' => $item->id,
                    'user_id'             => Auth::id(),
                    'qty'                 => $qty,
                    'amount'              => $returnAmount,
                    'reason'              => $reason,
                    'created_at'          => now()->timezone('Asia/Jakarta'),
                    'updated_at'          => now()->timezone('Asia/Jakarta'),
                ]);

                // 2. Update returned_qty on the item
                DB::table('transactionitem')
                    ->where('id', $item->id)
                    ->update(['returned_qty' => $item->returned_qty + $qty]);

                // 3. Restore stock
                DB::table('obat')
                    ->where('id', $item->obat_id)
                    ->increment('stok', $qty);
            }

            if ($totalReturn <= 0) {
                throw new \RuntimeException('Tidak ada item yang direturn.');
            }

            // 4. Update transaction status and total_return
            DB::table('transaction')
                ->where('id', $transactionId)
                ->update([
                    'total_return' => $transaction->total_return + $totalReturn,
                    'status'       => 'RETURN',
                    'updated_at'   => now()->timezone('Asia/Jakarta'),
                ]);
        });
    }

    /**
     * Void a transaction and restore all item stock.
     *
     * @throws \Throwable
     */
    public function processVoid(int $transactionId, string $voidReason): void
    {
        DB::transaction(function () use ($transactionId, $voidReason): void {
            /** @var Transaction $trx */
            $trx = Transaction::with('items')->findOrFail($transactionId);

            if ($trx->status === 'VOID') {
                throw new \RuntimeException('Transaksi sudah di-VOID.');
            }

            $trx->update([
                'status'      => 'VOID',
                'void_reason' => $voidReason,
                'void_by'     => Auth::id(),
                'void_at'     => Carbon::now()->timezone('Asia/Jakarta'),
            ]);

            foreach ($trx->items as $item) {
                Obat::where('id', $item->obat_id)->increment('stok', $item->qty);
            }
        });
    }
}
