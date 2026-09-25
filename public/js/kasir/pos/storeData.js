const btnBayar = document.getElementById('btnBayar')
let isBayar = false

const disableButton = e => {
  e.target.disabled = true
  e.target.style.cursor = 'not-allowed'
  e.target.style.opacity = '0.75'
  e.target.classList.remove('active:scale-95')
}
const resetButton = e => {
  e.target.disabled = false
  e.target.style.cursor = 'pointer'
  e.target.style.opacity = '1'
  e.target.classList.add('active:scale-95')
}

const toggleAlert = (alert) => {
  alert.classList.toggle('hidden')
  alert.classList.toggle('alertAnimate')
}

const bayar = async e => {
  e.preventDefault()

  if (data.length === 0) {
    alert('Keranjang kosong!')
    return
  }
  if (parseNumber(textTotalKembalian.textContent) < 0) {
    alert('Uang kurang!')
    return
  }

  if (!isBayar) {
    isBayar = true
    disableButton(e)

    try {
      const response = await fetch(`${BASE_URL}pos`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          cart: [...data],
          totalTransaction: parseNumber(textTotal.textContent),
          totalPaid: parseNumber(textTotalBayar.textContent),
          totalChange: parseNumber(textTotalKembalian.textContent)
        })
      })

      const d = await response.json()

      if (d.status === 'success' && d.redirect_url) {
        // ✅ langsung redirect ke halaman struk
        window.location.href = d.redirect_url
        return
      }

      // jika tidak redirect, tetap tampilkan alert sukses
      const alert = document.getElementById('alert')
      alert.textContent = d?.message || 'Transaksi berhasil!'
      toggleAlert(alert)
      setTimeout(() => toggleAlert(alert), 3100)

      inputTotalBayar.value = ''
      resetButton(e)
      data.length = 0
      updateTable()
      updateTextHarga()
      getDataObat()
      isBayar = false

    } catch (err) {
      console.error(err)
      alert('Terjadi kesalahan saat menyimpan transaksi.')
      resetButton(e)
      isBayar = false
    }
  }
}

btnBayar.addEventListener('click', bayar)
inputTotalBayar.addEventListener('inputTotalBayar', () => {
  let val = inputTotalBayar.value
  val = val.replace(/[^0-9]/g, '')
  if (val === '') val = '0'
  inputTotalBayar.value = formatRupiah(val)
  updateTextHarga()
})