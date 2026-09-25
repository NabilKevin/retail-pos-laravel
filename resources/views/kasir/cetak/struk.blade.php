@extends('kasir.layouts.app')

@section('title', 'Struk - Kasir')
@section('page-title', 'Struk')

@section('content')
    <div class="flex justify-center py-8">
        <div id="strukArea"
            class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md w-[280px] text-sm print:w-full print:shadow-none print:p-0">

            {{-- === STYLE STRUK === --}}
            <style>
                #strukArea {
                    width: 250px;
                    font-family: 'Courier New', monospace;
                    font-size: 11px;
                    line-height: 1.3;
                    color: #000;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 11px;
                }

                td {
                    padding: 1px 0;
                    vertical-align: top;
                }

                .border-dashed {
                    border-top: 1px dashed #000 !important;
                    margin: 4px 0;
                }

                .text-center {
                    text-align: center;
                }
            </style>

            {{-- === AREA STRUK === --}}
            <div class="struk font-mono text-gray-800 dark:text-gray-100" id="struk">
                <div class="text-center font-bold text-base">
                    <div>Toko Obat</div>
                    <div class="text-[11px] font-mono font-normal mt-1 leading-tight">
                        Jl. Swadaya No.04 8, RT.8/RW.12, Jatinegara, Kec. Cakung,<br>
                        Kota Jakarta Timur, DKI Jakarta 13930
                    </div>
                </div>

                <div class="border-dashed"></div>

                {{-- Info Transaksi --}}
                <table>
                    <tr>
                        <td>No. Transaksi</td>
                        <td class="text-right">{{ $transaction->kode }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td class="text-right">{{ date('d/m/Y H:i:s', strtotime($transaction->created_at)) }}</td>
                    </tr>
                    <tr>
                        <td>Kasir</td>
                        <td class="text-right">{{ $transaction->kasir ?? 'Kasir' }}</td>
                    </tr>
                </table>

                <div class="border-dashed"></div>

                {{-- Detail Barang --}}
                <table>
                    @foreach ($transaction->items as $item)
                        @if ($item->qty_final > 0)
                            <tr>
                                <td colspan="3" class="font-bold">{{ $item->obat->nama }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="pl-2">
                                    {{ $item->qty_final }} x {{ formatRupiah($item->harga_jual) }}
                                    @if ($item->qty_return > 0)
                                        <div class="text-[9px] italic text-red-600">
                                            Return {{ $item->qty_return }} : - {{ formatRupiah($item->subtotal_return) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-right">
                                    {{ formatRupiah($item->subtotal_final) }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </table>

                <div class="border-dashed"></div>

                {{-- Total --}}
                <table>
                    <tr>
                        <td>Total</td>
                        <td class="text-right">{{ formatRupiah($transaction->total_final) }}</td>
                    </tr>

                    @if ($transaction->total_return > 0)
                        <tr>
                            <td>Return</td>
                            <td class="text-right text-red-600">
                                -{{ formatRupiah($transaction->total_return) }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td>Tunai</td>
                        <td class="text-right">{{ formatRupiah($transaction->total_dibayar) }}</td>
                    </tr>
                    <tr>
                        <td>Kembali</td>
                        <td class="text-right">{{ formatRupiah($transaction->total_kembalian) }}</td>
                    </tr>
                </table>

                <div class="border-dashed"></div>

                {{-- Pesan --}}
                <div class="text-center text-[10px]">
                    <p>Terima Kasih!</p>
                    <p>Semoga Lekas Sembuh</p>
                </div>

                <div class="border-dashed"></div>

                <div class="text-center text-[9px] mt-2 italic">
                    <p>Struk ini merupakan bukti pembayaran yang sah</p>
                </div>

                <div class="border-dashed"></div>

                <div class="text-center text-[10px] mt-1 font-bold">
                    <p>Powered by EasyFlow</p>
                </div>
            </div>

            {{-- === TOMBOL AKSI (TIDAK DICETAK) === --}}
            <div class="no-print flex justify-center items-center gap-3 mt-6">
                {{-- Tombol Print RawBT --}}
                <button onclick="printRawBT()"
                    class="px-4 py-1.5 bg-blue-500 text-white text-sm font-mono rounded-md hover:bg-blue-600 transition border border-blue-700">
                    🖨️ Print RawBT
                </button>

                {{-- Tombol Print Windows --}}
                <button onclick="printWindow()"
                    class="px-4 py-1.5 bg-indigo-500 text-white text-sm font-mono rounded-md hover:bg-indigo-600 transition border border-indigo-700">
                    💻 Print Windows
                </button>

                {{-- Tombol Kembali --}}
                <a href="{{ route('kasir.transaksi') }}"
                    class="px-4 py-1.5 bg-green-500 text-white text-sm font-mono rounded-md hover:bg-green-600 transition border border-green-700">
                    ✅ Selesai
                </a>
            </div>


        </div>
    </div>

    {{-- === SCRIPT RAWBT === --}}
    <script>
        function centerText(text, width = 36) {
            text = text.trim();
            if (text.length >= width) return text;
            let left = Math.floor((width - text.length) / 2);
            return ' '.repeat(left) + text;
        }

        function decodeHtml(str) {
            const txt = document.createElement("textarea");
            txt.innerHTML = str;
            return txt.value;
        }

        function getReceiptText(width = 36) {
            const toko = "Toko Obat";
            const alamat = [
                "Jl. Swadaya No.04 8, RT.8/RW.12,",
                "Jatinegara, Kec. Cakung,",
                "Kota Jakarta Timur, DKI Jakarta 13930"
            ];

            const transaksi = {
                kode: "{{ $transaction->kode }}",
                tanggal: "{{ date('d/m/Y H:i:s', strtotime($transaction->created_at)) }}",
                kasir: "{{ $transaction->kasir ?? 'Kasir' }}",
                total: "{{ formatRupiah($transaction->total_final) }}",
                return: "{{ formatRupiah($transaction->total_return) }}",
                tunai: "{{ formatRupiah($transaction->total_dibayar) }}",
                kembali: "{{ formatRupiah($transaction->total_kembalian) }}"
            };

            let header = "\n" + centerText(toko, width) + "\n";
            alamat.forEach(line => header += centerText(line, width) + "\n");
            header += "-".repeat(40);

            // Daftar item
            let items = "";
            @foreach ($transaction->items as $item)
                @if ($item->qty_final > 0)
                    <?php
                    $nama = addslashes($item->obat->nama);
                    $qty = $item->qty_final;
                    $hargaSatuan = formatRupiah($item->harga_jual);
                    $subtotal = formatRupiah($item->subtotal_final);
                    $returnQty = $item->qty_return;
                    ?> {
                        let nama = decodeHtml("{{ $nama }}");

                        // Nama obat (1 baris sendiri)
                        items += nama + "\n";

                        // Kolom tetap
                        let kiri = `  {{ $qty }} x {{ $hargaSatuan }}`.padEnd(18, " ");
                        let kanan = "{{ $subtotal }}".padStart(14, " ");

                        items += kiri + kanan + "\n";
                        if ({{ $returnQty }} > 0) {
                            items += "  Return {{ $returnQty }}\n";
                        }
                    }
                @endif
                @endforeach


                let footer = "";
                footer += "-".repeat(40) + "\n";
                footer += `Total   : ${transaksi.total}\n`;
                footer += `Tunai   : ${transaksi.tunai}\n`;
                footer += `Kembali : ${transaksi.kembali}\n`;
                if (parseInt("{{ $transaction->total_return }}") > 0) {
                    footer += `Return  : -${transaksi.return}\n`;
                }
                footer += "-".repeat(40) + "\n";
                footer += centerText("Terima Kasih!", width) + "\n";
                footer += centerText("Semoga Lekas Sembuh", width) + "\n";
                footer += "-".repeat(40) + "\n";
                footer += centerText("Struk ini merupakan bukti pembayaran", width) + "\n";
                footer += centerText("yang sah", width) + "\n";
                footer += "-".repeat(40) + "\n";
                footer += centerText("Powered by EasyFlow", width) + "\n";

                return `${header}
No.Transaksi : ${transaksi.kode}
Tanggal      : ${transaksi.tanggal}
Kasir        : ${transaksi.kasir}
${"-".repeat(40)}
${items}${footer}`;
            }

            function getReceiptTextWindow(width = 34) {
                const toko = "Toko Obat";
                const alamat = [
                    "Jl. Swadaya No.04 8, RT.8/RW.12,",
                    "Jatinegara, Kec. Cakung,",
                    "Kota Jakarta Timur, DKI Jakarta 13930"
                ];

                const transaksi = {
                    kode: "{{ $transaction->kode }}",
                    tanggal: "{{ date('d/m/Y H:i:s', strtotime($transaction->created_at)) }}",
                    kasir: "{{ $transaction->kasir ?? 'Kasir' }}",
                    total: "{{ formatRupiah($transaction->total_final) }}",
                    return: "{{ formatRupiah($transaction->total_return) }}",
                    tunai: "{{ formatRupiah($transaction->total_dibayar) }}",
                    kembali: "{{ formatRupiah($transaction->total_kembalian) }}"
                };

                let header = "\n" + centerText(toko, width) + "\n";
                alamat.forEach(line => header += centerText(line, width) + "\n");
                header += "-".repeat(40);

                // Daftar item
                let items = "";
                @foreach ($transaction->items as $item)
                    @if ($item->qty_final > 0)
                    <?php
                    $nama = addslashes($item->obat->nama);
                    $qty = $item->qty_final;
                    $hargaSatuan = formatRupiah($item->harga_jual);
                    $subtotal = formatRupiah($item->subtotal_final);
                    $returnQty = $item->qty_return;
                    ?> {
                        let nama = decodeHtml("{{ $nama }}");

                        // Nama obat (1 baris sendiri)
                        items += nama + "\n";

                        // Kolom tetap
                        let kiri = `  {{ $qty }} x {{ $hargaSatuan }}`.padEnd(18, " ");
                        let kanan = "{{ $subtotal }}".padStart(14, " ");

                        items += kiri + kanan + "\n";
                        if ({{ $returnQty }} > 0) {
                            items += "  Return {{ $returnQty }} : -{{ formatRupiah($item->subtotal_return) }}\n";
                        }
                    }
                    @endif
                @endforeach


                let footer = "";
                footer += "-".repeat(40) + "\n";
                footer += `Total   : ${transaksi.total}\n`;
                if (parseInt("{{ $transaction->total_return }}") > 0) {
                    footer += `Return  : -${transaksi.return}\n`;
                }
                footer += `Tunai   : ${transaksi.tunai}\n`;
                footer += `Kembali : ${transaksi.kembali}\n`;
                footer += "-".repeat(40) + "\n";
                
                footer += centerText("Terima Kasih!", width) + "\n";
                footer += centerText("Semoga Lekas Sembuh", width) + "\n";
                footer += "-".repeat(40) + "\n";
                footer += centerText("Struk ini merupakan bukti pembayaran", width) + "\n";
                footer += centerText("yang sah", width) + "\n";
                footer += "-".repeat(40) + "\n";
                footer += centerText("Powered by EasyFlow", width) + "\n";

                return `${header}
No.Transaksi : ${transaksi.kode}
Tanggal      : ${transaksi.tanggal}
Kasir        : ${transaksi.kasir}
${"-".repeat(40)}
${items}${footer}`;
            }

            // ======================================================
            // ✅ 1. Print ke RawBT di Android
            // ======================================================
            function printRawBT() {
                const escposReset = "\x1B\x40";
                const escposSmallFont = "\x1B\x4D\x01"; // gunakan font kecil agar 32 kolom muat
                const strukText = escposReset + escposSmallFont + getReceiptText(32);
                const encoded = encodeURIComponent(strukText);
                window.location.href = "intent:" + encoded + "#Intent;scheme=rawbt;package=ru.a402d.rawbtprinter;end;";
            }

            // ======================================================
            // ✅ 2. Print langsung di Browser (Laptop)
            // ======================================================
            function printWindow() {
                // Ambil hasil teks struk dari fungsi getReceiptText()
                const receipt = getReceiptTextWindow(34).replace(/\n/g, "<br>");


                // Buat container sementara untuk tampilan print
                const printArea = document.createElement("div");
                printArea.innerHTML = `
                <div style="width: 290px; font-family: 'Courier New', monospace; font-size: 11px; line-height: 1.3; color: #000;">
                    ${receipt}
                </div>
            `;

                // Simpan isi halaman asli
                const originalContents = document.body.innerHTML;

                // Ganti isi halaman dengan tampilan print
                document.body.innerHTML = printArea.innerHTML;

                // Jalankan dialog print browser
                window.print();

                // Setelah selesai print, kembalikan isi halaman semula
                document.body.innerHTML = originalContents;
            }
    </script>







@endsection
