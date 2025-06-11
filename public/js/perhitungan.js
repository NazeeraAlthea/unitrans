function getBobot() {
    let total =
        Number(document.getElementById("slider-biaya").value) +
        Number(document.getElementById("slider-waktu").value) +
        Number(document.getElementById("slider-keamanan").value) +
        Number(document.getElementById("slider-kenyamanan").value) +
        Number(document.getElementById("slider-aksesbilitas").value);
    return [
        Number(document.getElementById("slider-biaya").value) / total,
        Number(document.getElementById("slider-waktu").value) / total,
        Number(document.getElementById("slider-keamanan").value) / total,
        Number(document.getElementById("slider-kenyamanan").value) / total,
        Number(document.getElementById("slider-aksesbilitas").value) / total,
    ];
}

function konversiWaktuKeMenit(waktuText) {
    if (!waktuText) return 99999;
    let total = 0;
    if (waktuText.includes("jam")) {
        let jam = parseInt(waktuText.split("jam")[0]);
        total += jam * 60;
        let menit = waktuText.split("jam")[1].match(/\d+/);
        if (menit) total += parseInt(menit[0]);
    } else if (waktuText.includes("menit")) {
        let menit = waktuText.match(/\d+/);
        if (menit) total += parseInt(menit[0]);
    } else if (waktuText.includes("hour")) {
        // UNTUK INGGRIS!
        let jam = parseInt(waktuText.split("hour")[0]);
        total += jam * 60;
        let menit = waktuText.split("hour")[1].match(/\d+/);
        if (menit) total += parseInt(menit[0]);
    } else if (waktuText.includes("min")) {
        let menit = waktuText.match(/\d+/);
        if (menit) total += parseInt(menit[0]);
    }
    return total;
}

// Pastikan waktuMinute sudah update di setiap transportAlternatif
function updateWaktuMinute() {
    window.transportAlternatif.forEach(function (alt) {
        if (typeof alt.waktu === "string") {
            alt.waktuMinute = konversiWaktuKeMenit(alt.waktu);
        } else {
            alt.waktuMinute = 99999;
        }
    });
}

// Event tombol cek rekomendasi
document.getElementById("cek_rekomendasi").onclick = function () {
    

    updateWaktuMinute();
    let bobot = getBobot();
    // Ambil transportasi yang dipilih user jika pakai checkbox (jika tidak, bisa seluruh window.transportAlternatif)
    let alternatif = window.transportAlternatif.filter((alt) => {
        let cb = document.querySelector(
            `input[name="transportasi[]"][value="${alt.id_transportasi}"]`
        );
        return cb && cb.checked;
    });

    console.log("Alternatif yang dikirim:", alternatif);
    console.log("Bobot:", bobot);
    fetch("/hitung-copras", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content"),
        },
        body: JSON.stringify({
            alternatif: alternatif,
            bobot: bobot,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
             console.log("RESPONSE DATA:", data);
             if (data && data.id_perhitungan) {
                 // Ini redirect ke page hasil rekomendasi, bukan tampil di page ini!
                 // window.location.href = `/hasil-rekomendasi/${data.id_perhitungan}`;
                 console.log("RESPONSE DATA:", data);
            } else {
                alert("Gagal mendapatkan hasil! Silakan coba ulangi.");
            }
        })
        .catch((err) => {
            alert("Terjadi error saat hitung rekomendasi.");
            console.error(err);
        });
};
