const form = document.querySelector('form');

const afterScan = (decodedText) => {
  document.querySelector('input#kode_barcode, input.kode_barcode').value = decodedText
}

document.querySelector('button[type=submit]').addEventListener('click', function() {
    let allInputFilled = true
    const inputs = form.querySelectorAll("input, select"); // ambil semua input
    inputs.forEach(input => {
        if (input.type !== 'hidden' && !input.value.trim()) {
            allInputFilled = false
        }
    });
    if(allInputFilled) {
        setTimeout(() => {
            this.disabled = true;
            this.style.opacity = '0.5';
            this.style.cursor = 'not-allowed';
        }, 1)
    }

})

const hidden_modal = document.getElementById('harga_modal_hidden');
const hidden_jual = document.getElementById('harga_jual_hidden');

document.getElementById('harga_modal').addEventListener('input', e => formatInputNumber(e, hidden_modal));
document.getElementById('harga_jual').addEventListener('input', e => formatInputNumber(e, hidden_jual));
