const inputPage = document.querySelector('#inputPage')
inputPage.addEventListener('input', function() {
    const min = parseInt(this.min) || 1;
    const max = parseInt(this.max) || 1;
    let value = this.value.trim() === '' ? null : parseInt(this.value);

    // Jika input kosong, biarkan (opsional)
    if (value === null || isNaN(value)) {
        return;
    }

    // Clamp nilai ke dalam rentang [min, max]
    if (value < min) {
        this.value = min;
    } else if (value > max) {
        this.value = max;
    }
});

document.querySelector('#inputPage').addEventListener('change', function() {
    const max = parseInt(this.max) || 1;
    let value = this.value.trim() === '' ? null : parseInt(this.value);

    if (value > max) {
        this.value = max;
    }
})