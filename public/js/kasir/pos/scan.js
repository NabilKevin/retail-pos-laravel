const afterScan = (decodedText) => {
  const obat = obats.filter(a => a.kode_barcode === decodedText)
  if(obat.length === 0) {
      alert('Kode barang tidak ada, coba scan ulang!')
  } else {
      addToCart(obat[0])
  }
}