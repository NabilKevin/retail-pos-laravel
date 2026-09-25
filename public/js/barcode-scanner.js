document.addEventListener("DOMContentLoaded", function () {
    let html5QrCode;
    let isScanning = false;

    const readerElem = document.getElementById("reader");
    const button = document.getElementById("start-scan");
    const previewCameraParent = document.getElementById("previewCamera");

    const closeCam = () => {
        html5QrCode.stop().then(() => {
            readerElem.parentElement.classList.add('hidden')
            isScanning = false;
        }).catch(err => {
            console.error("Stop gagal", err);
        });
    }

    button.addEventListener("click", function () {
        if (isScanning) return; // supaya ga double start

        html5QrCode = new Html5Qrcode("reader");
        readerElem.parentElement.classList.remove('hidden')
        isScanning = true;

        html5QrCode.start(
            { facingMode: "environment" }, // pakai kamera belakang (kalau ada)
            {
                fps: 10,      // frame per detik
                qrbox: { width: 250, height: 250 } // area scan
            },
            (decodedText, decodedResult) => {
                const format = decodedResult.result.format.formatName;

                if (["CODE_128", "EAN_13"].includes(format)) {
                    // Masukkan hasil scan ke input
                    afterScan(decodedText)
    
                    // Stop kamera setelah dapat hasil
                    closeCam()
                }
            },
            (errorMessage) => {
                // console.error(errorMessage)
            }
        ).catch(err => {
            console.error("Start gagal", err);
            isScanning = false;
        });
    });

    previewCameraParent.addEventListener('click', function(e) {
        if(e.target.id === this.id && !this.classList.contains('hidden')) {
            closeCam()
        }
    })
});