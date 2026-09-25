const afterScan = (decodedText) => {
  const input = document.querySelector('input#kode_barcode, input.kode_barcode')
  input.value = decodedText
  input.closest('form').submit();
}