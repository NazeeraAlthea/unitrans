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

function hitungCOPRAS(transportAlternatif, bobot) {
    let n = transportAlternatif.length;
    // Pastikan nilai tidak ada yang nol
    let matriks = transportAlternatif.map((alt) => [
        Number(alt.harga) > 0 ? Number(alt.harga) : 1,
        Number(alt.waktuMinute) > 0 ? Number(alt.waktuMinute) : 1,
        Number(alt.keamanan) > 0 ? Number(alt.keamanan) : 1,
        Number(alt.kenyamanan) > 0 ? Number(alt.kenyamanan) : 1,
        Number(alt.aksesbilitas) > 0 ? Number(alt.aksesbilitas) : 1,
    ]);
    let jenisKriteria = ["cost", "cost", "benefit", "benefit", "benefit"];
    // Normalisasi
    let norm = [];
    for (let j = 0; j < 5; j++) {
        let kolom = matriks.map((row) => row[j]);
        if (jenisKriteria[j] === "benefit") {
            let sum = kolom.reduce((a, b) => a + b, 0);
            if (sum === 0) sum = 1; // cegah bagi 0
            norm[j] = kolom.map((val) => val / sum);
        } else {
            // cost
            let min = Math.min(...kolom.filter((x) => x > 0));
            if (!isFinite(min) || min === 0) min = 1;
            norm[j] = kolom.map((val) => min / (val || 1));
        }
    }
    // Transpose
    let normTrans = [];
    for (let i = 0; i < n; i++) {
        normTrans[i] = norm.map((kol) => kol[i]);
    }
    // Matriks berbobot
    let normBobot = normTrans.map((row) => row.map((val, j) => val * bobot[j]));
    // S+ (benefit), S- (cost)
    let benefitIdx = [2, 3, 4],
        costIdx = [0, 1];
    let Splus = normBobot.map((row) =>
        benefitIdx.reduce((sum, idx) => sum + row[idx], 0)
    );
    let Smin = normBobot.map((row) =>
        costIdx.reduce((sum, idx) => sum + row[idx], 0)
    );
    // Qi
    let SminTotal = Smin.reduce((a, b) => a + b, 0);
    let SminMin = Math.min(...Smin.filter((x) => x > 0));
    // -- fix: jika cost sangat jomplang (misal ada yang jauh lebih murah), COPRAS Smin akan memperbesar Qi pada alternatif dengan cost sangat besar
    // -- Jika ingin COPRAS lebih adil, jangan pakai bobot cost=100%, selalu gabung benefit
    let Qi = Splus.map((sPlus, i) => {
        // Smin[i] tidak boleh 0!
        let costPart = Smin[i] > 0 ? (SminMin * SminTotal) / Smin[i] : 0;
        return sPlus + costPart;
    });
    // Ui
    let Qmax = Math.max(...Qi);
    let Ui = Qi.map((q) => (q / Qmax) * 100);
    // Return ranking
    return transportAlternatif
        .map((alt, i) => ({
            nama: alt.nama_transportasi,
            Qi: Qi[i],
            Ui: Ui[i],
        }))
        .sort((a, b) => b.Ui - a.Ui); // urutkan dari skor tertinggi ke rendah
}

// Event tombol cek rekomendasi
document.getElementById("cek_rekomendasi").onclick = function () {
    updateWaktuMinute();
    let bobot = getBobot();
    // Ambil transportasi yang dipilih user jika pakai checkbox (jika tidak, bisa seluruh window.transportAlternatif)
    let alternatif = window.transportAlternatif.filter(alt => {
        let cb = document.querySelector(`input[name="transportasi[]"][value="${alt.id_transportasi}"]`);
        return cb && cb.checked;
    });

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
        if (data && data.id_perhitungan) {
            // Ini redirect ke page hasil rekomendasi, bukan tampil di page ini!
            window.location.href = `/hasil-rekomendasi/${data.id_perhitungan}`;
        } else {
            alert("Gagal mendapatkan hasil! Silakan coba ulangi.");
        }
    })
    .catch((err) => {
        alert("Terjadi error saat hitung rekomendasi.");
        console.error(err);
    });
};

