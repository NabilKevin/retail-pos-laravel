// =======================
// FUNGSI PEMBANTU (UTILITY)
// =======================
const closeAllModals = () => {
    document.getElementById("trx-modal").classList.add("hidden");
    document.getElementById("profit-modal").classList.add("hidden");
    document.getElementById("void-modal").classList.add("hidden");
};
let activeTransaction = null;
function openModalTransaksi(e) {
    e.stopPropagation();

    const id = e.currentTarget.dataset.id;
    const trx = window.transaksis.find((t) => t.id == id);
    if (!trx) return;

    activeTransaction = trx;

    document.getElementById("trx-modal").classList.remove("hidden");

    document.getElementById("trxKode").textContent = trx.kode;
    document.getElementById("trxKasir").textContent = trx.user?.nama ?? "-";
    document.getElementById("trxWaktu").textContent = new Date(
        trx.created_at
    ).toLocaleString();

    // void at

    // return at

    // STATUS BADGE
    const statusEl = document.getElementById("trxStatus");
    statusEl.textContent = trx.status;
    statusEl.className =
        "inline-block px-3 py-1 rounded-full text-xs font-semibold " +
        (trx.status === "SUCCESS"
            ? "bg-green-100 text-green-700"
            : trx.status === "VOID"
            ? "bg-red-100 text-red-700"
            : "bg-yellow-100 text-yellow-700");
    if (trx.status === "VOID") {
        document.getElementById("trxVoidAt").textContent = trx.void_at
            ? new Date(trx.void_at).toLocaleString()
            : "-";
            // Hapus elemen returnAt jika transaksi void
        document.getElementById("trxReturnAt").textContent = "-";
            
    } else if (trx.status === "RETURN" || trx.returns.length > 0) {
        document.getElementById("trxReturnAt").textContent = trx.returns[0]?.created_at
            ? new Date(trx.returns[0].created_at).toLocaleString()
            : "-";
        document.getElementById("trxVoidAt").textContent = "-";
    } else {
        document.getElementById("trxVoidAt").textContent = "-";
        document.getElementById("trxReturnAt").textContent = "-";
    }
    // ITEMS
    let rows = "";
    let totalAfterReturn = 0;

    trx.items.forEach((item) => {
        const returned = item.returned_qty ?? 0;
        const sisa = item.qty - returned;
        const subtotal = sisa * item.harga_jual;
        totalAfterReturn += subtotal;

        rows += `
      <tr>
        <td class="px-3 py-2">${item.obat.nama}</td>
        <td class="px-3 py-2 text-center">${item.qty}</td>
        <td class="px-3 py-2 text-center text-red-600">${returned}</td>
        <td class="px-3 py-2 text-right">Rp ${formatNumber(
            item.harga_jual
        )}</td>
        <td class="px-3 py-2 text-right">Rp ${formatNumber(subtotal)}</td>
      </tr>
    `;
    });

    document.getElementById("trx-items").innerHTML = rows;

    const dibayar = trx.total_dibayar;
    const kembalian = dibayar - totalAfterReturn;

    document.getElementById("trxTotal").textContent =
        "Rp " + formatNumber(totalAfterReturn);
    document.getElementById("trxTotalBayar").textContent =
        "Rp " + formatNumber(dibayar);
    document.getElementById("trxTotalKembalian").textContent =
        "Rp " + formatNumber(kembalian);

    // VOID INFO
    const voidInfo = document.getElementById("void-info");
    if (trx.status === "VOID") {
        voidInfo.classList.remove("hidden");
        document.getElementById("voidReason").textContent =
            trx.void_reason ?? "-";
        document.getElementById("voidBy").textContent =
            trx.void_by?.nama ?? "-";
    } else {
        voidInfo.classList.add("hidden");
    }

    // RETURN INFO
    const returnSection = document.getElementById("return-section");
    if (trx.returns?.length) {
        returnSection.classList.remove("hidden");
        let ret = "";
        trx.returns.forEach((r) => {
            ret += `
        <tr>
          <td class="px-3 py-2">${r.item.obat.nama}</td>
          <td class="px-3 py-2 text-center">${r.qty}</td>
          <td class="px-3 py-2 text-right">Rp ${formatNumber(r.amount)}</td>
          <td class="px-3 py-2">${r.reason}</td>
          <td class="px-3 py-2">${r.user?.nama ?? "-"}</td>
        </tr>
      `;
        });
        document.getElementById("returnTable").innerHTML = ret;
    } else {
        returnSection.classList.add("hidden");
    }
}
function closeModalTransaksi() {
    document.getElementById("trx-modal").classList.add("hidden");
}

document.querySelectorAll(".btn-detail").forEach((btn) => {
    btn.addEventListener("click", openModalTransaksi);
});

// =======================
// 2. PROFIT MODAL (Cek Keuntungan)
// =======================
const openProfitModal = async (e) => {
    e?.preventDefault();

    try {
        const params = new URLSearchParams(window.location.search);
        // Memastikan URL bersih dari double slash
        const targetUrl = `${
            window.BASE_URL
        }kasir/transaksi/profit?${params.toString()}`;

        const res = await fetch(targetUrl);
        if (!res.ok) throw new Error("Gagal mengambil data");

        const data = await res.json();

        closeAllModals(); // Tutup modal lain sebelum buka profit

        document.getElementById("profitTotalJual").innerText =
            "Rp " + formatNumber(data.total_jual);
        document.getElementById("profitTotalModal").innerText =
            "Rp " + formatNumber(data.total_modal);
        document.getElementById("profitTotalUntung").innerText =
            "Rp " + formatNumber(data.keuntungan);
        document.getElementById("profitMargin").innerText = data.margin + "%";

        document.getElementById("profit-modal").classList.remove("hidden");
    } catch (err) {
        console.error("Profit Error:", err);
        alert("Gagal memuat data keuntungan. Pastikan filter tanggal benar.");
    }
};
btnprofitClose = document.getElementById("profit-close");
btnprofitClose?.addEventListener("click", closeAllModals);
// jika button id profit-close di klik
window.closeProfitModal = function () {
    closeAllModals();
};

let activeReturnTransaction = null;

function openReturnModal(trxId, kode) {
    activeReturnTransaction = trxId;
    document.getElementById("returnKode").innerText = kode;

    const modal = document.getElementById("return-modal");
    modal.classList.remove("hidden");

    document.getElementById(
        "returnForm"
    ).action = `${BASE_URL}kasir/transaksi/${trxId}/return`;

    loadReturnItems(trxId);
}

function closeReturnModal() {
    document.getElementById("return-modal").classList.add("hidden");
    document.getElementById("return-items").innerHTML = "";
}

function loadReturnItems(trxId) {
    const trx = window.transaksis.find((t) => t.id === trxId);
    if (!trx || !trx.items) return;

    let html = "";

    trx.items.forEach((item) => {
        const sisa = item.qty - item.returned_qty;
        if (sisa <= 0) return;

        html += `
            <tr class="border-b dark:border-neutral-800">
                <td class="px-3 py-2">${item.obat.nama}</td>
                <td class="text-center">${item.qty}</td>
                <td class="text-center text-green-600">${sisa}</td>
                <td class="text-center">
                    <input type="number"
                        name="items[${item.id}]"
                        min="0"
                        max="${sisa}"
                        value="0"
                        class="w-20 text-center rounded-md border
                        dark:border-neutral-700 bg-white dark:bg-neutral-900">
                </td>
            </tr>
        `;
    });

    document.getElementById("return-items").innerHTML =
        html ||
        `<tr><td colspan="4" class="text-center py-4 text-muted-foreground">
            Tidak ada item yang bisa direturn
        </td></tr>`;
}
// =======================
// 3. VOID MODAL (Global)
// =======================
window.openVoidModal = function (id, kode, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    closeAllModals(); // Tutup modal lain (termasuk modal detail jika sedang terbuka)

    document.getElementById("voidKode").innerText = kode;
    document.getElementById(
        "voidForm"
    ).action = `${window.BASE_URL}kasir/transaksi/${id}/void`;
    document.getElementById("void-modal").classList.remove("hidden");
};

window.closeVoidModal = function () {
    closeAllModals();
};

// =======================
// EVENT LISTENERS
// =======================

// Inisialisasi tombol setelah DOM siap
document.addEventListener("DOMContentLoaded", () => {
    // Tombol Detail
    document.querySelectorAll(".btn-detail").forEach((btn) => {
        btn.addEventListener("click", openModalTransaksi);
    });

    // Tombol Profit
    const btnProfit = document.getElementById("btn-profit");
    if (btnProfit) btnProfit.onclick = openProfitModal;

    // Tombol Close Detail
    document
        .getElementById("trx-close-bottom")
        ?.addEventListener("click", closeAllModals);

    // Klik Overlay untuk menutup
    document
        .querySelectorAll("[data-modal-overlay], [data-profit-overlay]")
        .forEach((el) => {
            el.onclick = closeAllModals;
        });
});
function printStruk() {
    if (!activeTransaction) {
        alert("Transaksi tidak ditemukan");
        return;
    }

    // Jika VOID → tidak boleh cetak struk
    if (activeTransaction.status === "VOID") {
        alert("Transaksi VOID tidak bisa dicetak");
        return;
    }

    // Gunakan KODE (lebih aman dari sisi user)
    window.open(
        `${window.BASE_URL}kasir/struk/${activeTransaction.kode}`,
        "_blank"
    );
}
// Print Struk
document.getElementById("trx-print")?.addEventListener("click", () => {
    const kode = document.getElementById("trxKode").textContent;
    if (kode) window.open(`${window.BASE_URL}kasir/struk/${kode}`, "_blank");
});
