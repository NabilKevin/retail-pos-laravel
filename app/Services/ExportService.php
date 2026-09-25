<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /**
     * Export transactions to an Excel (.xlsx) file and stream the download.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query  Pre-filtered Transaction query
     * @param  bool  $includeStatusColumn  Admin view shows a Status column; kasir view does not
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportTransaksi(\Illuminate\Database\Eloquent\Builder $query, bool $includeStatusColumn = true)
    {
        $transactions = (clone $query)
            ->with(['items.obat', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Transaksi');

        // Dynamic header based on role context
        $headers = $includeStatusColumn
            ? ['No', 'Kode Transaksi', 'Tanggal', 'Kasir', 'Status', 'Nama Obat', 'Qty', 'Modal', 'Total Jual', 'Keuntungan']
            : ['No', 'Kode Transaksi', 'Tanggal', 'Kasir', 'Nama Obat', 'Qty', 'Modal', 'Total Jual', 'Keuntungan'];

        $sheet->fromArray($headers, null, 'A1');

        $row        = 2;
        $no         = 1;
        $totalModal = 0;
        $totalJual  = 0;
        $totalUntung = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->status === 'VOID') {
                continue;
            }

            foreach ($transaction->items as $item) {
                $qtyNet = $item->qty - ($item->returned_qty ?? 0);
                if ($qtyNet <= 0) {
                    continue;
                }

                $modal  = $item->harga_modal * $qtyNet;
                $jual   = $item->harga_jual * $qtyNet;
                $untung = $jual - $modal;

                $rowData = $includeStatusColumn
                    ? [
                        $no++,
                        $transaction->kode,
                        optional($transaction->created_at)->format('Y-m-d H:i'),
                        optional($transaction->user)->nama ?? '-',
                        $transaction->status,
                        optional($item->obat)->nama ?? '-',
                        $qtyNet,
                        $modal,
                        $jual,
                        $untung,
                    ]
                    : [
                        $no++,
                        $transaction->kode,
                        optional($transaction->created_at)->format('Y-m-d H:i'),
                        optional($transaction->user)->nama ?? '-',
                        optional($item->obat)->nama ?? '-',
                        $qtyNet,
                        $modal,
                        $jual,
                        $untung,
                    ];

                $sheet->fromArray($rowData, null, 'A' . $row);

                $totalModal  += $modal;
                $totalJual   += $jual;
                $totalUntung += $untung;
                $row++;
            }
        }

        // Summary row
        $lastDataCol  = $includeStatusColumn ? 'I' : 'H';
        $labelCol     = $includeStatusColumn ? 'F' : 'E';
        $modalCol     = $includeStatusColumn ? 'H' : 'G';
        $jualCol      = $includeStatusColumn ? 'I' : 'H';
        $untungCol    = $includeStatusColumn ? 'J' : 'I';

        $sheet->setCellValue("{$labelCol}{$row}", 'TOTAL');
        $sheet->setCellValue("{$modalCol}{$row}", $totalModal);
        $sheet->setCellValue("{$jualCol}{$row}", $totalJual);
        $sheet->setCellValue("{$untungCol}{$row}", $totalUntung);

        // Style
        $lastHeaderCol = $includeStatusColumn ? 'J' : 'I';
        $sheet->getStyle("A1:{$lastHeaderCol}1")->getFont()->setBold(true);
        foreach (range('A', $lastHeaderCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Laporan_Transaksi_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    /**
     * Export expired obat list to Excel.
     */
    public function exportExpiredObat(\Illuminate\Database\Eloquent\Collection $obats): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->fromArray([['Nama Obat', 'Stok', 'Tanggal Expired', 'Status']], null, 'A1');

        $row = 2;
        foreach ($obats as $obat) {
            $sheet->fromArray([
                $obat->nama,
                $obat->stok,
                Carbon::parse($obat->expired_at)->format('d/m/Y'),
                'Expired',
            ], null, "A{$row}");
            $row++;
        }

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'barang-expired-' . now()->format('Y-m-d') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
