let asalLokasi = "";
let autocomplete;

function initAutocomplete() {
    const input = document.getElementById("asal_autocomplete");
    autocomplete = new google.maps.places.Autocomplete(input, {
        types: ["geocode"],
        componentRestrictions: {
            country: "id",
        },
    });
    autocomplete.addListener("place_changed", function () {
        const place = autocomplete.getPlace();
        asalLokasi = place.formatted_address || input.value;
    });
}
window.onload = initAutocomplete;

const tujuan = "Universitas Indonesia, Depok, Indonesia";
window.transportAlternatif = []; // Akan diisi lewat fetch!

const warnaShadow = {
    Kereta: { shadow: "shadow-inner-top-strong-orange", border: "border-orange-strong" },
    Bus: { shadow: "shadow-inner-top-strong-blue", border: "border-blue-strong" },
    "Ojek Online": { shadow: "shadow-inner-top-strong-green", border: "border-green-strong" },
    "Motor Pribadi": { shadow: "shadow-inner-top-strong-red", border: "border-red-strong" },
    "Mobil Pribadi": { shadow: "shadow-inner-top-strong-purple", border: "border-purple-strong" }
    // dst...
};

function renderSlider(namaAlt, aspek, nilai) {
    let slug = namaAlt.replace(/\s/g, "");
    let html = "";
    for (let i = 1; i <= 5; i++) {
        html += `
            <span
                class="inline-block w-6 h-6 rounded-full mx-1 border border-gray-400 cursor-pointer transition 
                    ${i <= nilai ? "bg-blue-400" : "bg-gray-200"}"
                data-alt="${slug}"
                data-aspek="${aspek}"
                data-value="${i}"
                title="${i}"
            ></span>
        `;
    }
    return html;
}

function renderTransport() {
    const list = window.transportAlternatif
        .map((alt) => {
            let slug = alt.nama_transportasi.replace(/\s/g, "");
            // Default value jika belum ada (setelah fetch)
            alt.harga = alt.harga ?? 0;
            alt.waktu = alt.waktu ?? null;
            alt.kenyamanan = alt.kenyamanan ?? 3;
            alt.keamanan = alt.keamanan ?? 3;
            alt.aksesbilitas = alt.aksesbilitas ?? 3;

            return `
        <label class="block mb-5">
            <input type="checkbox" class="hidden peer" name="transportasi[]" value="${alt.id_transportasi}">
            <div class="bg-white border-2 border-gray-300 rounded-2xl shadow flex flex-row items-center px-8 py-7 max-w-4xl mx-auto cursor-pointer
                transition peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200">
                <div class="flex flex-col items-center min-w-[120px]">
                    <span class="text-5xl mb-4">${alt.icon}</span>
                    <span class="text-2xl text-gray-600 font-light">${alt.nama_transportasi}</span>
                </div>
                <div class="border-l border-gray-300 h-28 mx-8"></div>
                <div class="flex flex-col justify-center min-w-[120px] gap-5">
                    <div>
                        <div class="text-lg text-gray-700 font-semibold">Harga</div>
                        <div class="text-lg text-gray-600 font-light" id="harga-${slug}">Rp ${alt.harga.toLocaleString("id-ID")}</div>
                    </div>
                    <div>
                        <div class="text-lg text-gray-700 font-semibold">Waktu</div>
                        <div class="text-lg text-gray-600 font-light" id="waktu-${slug}">${alt.waktu ? alt.waktu : "-"}</div>
                    </div>
                </div>
                <div class="flex-1 grid grid-cols-2 gap-x-12 gap-y-6 pl-8">
                    <div>
                        <div class="text-lg font-semibold text-gray-700 mb-2">Kenyamanan</div>
                        <div class="flex items-center gap-2" id="slider-${slug}-kenyamanan">
                            ${renderSlider(alt.nama_transportasi, "kenyamanan", alt.kenyamanan)}
                        </div>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-700 mb-2">Aksesbilitas</div>
                        <div class="flex items-center gap-2" id="slider-${slug}-aksesbilitas">
                            ${renderSlider(alt.nama_transportasi, "aksesbilitas", alt.aksesbilitas)}
                        </div>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-700 mb-2">Keamanan</div>
                        <div class="flex items-center gap-2" id="slider-${slug}-keamanan">
                            ${renderSlider(alt.nama_transportasi, "keamanan", alt.keamanan)}
                        </div>
                    </div>
                    <div></div>
                </div>
            </div>
        </label>
        `;
        })
        .join("");
    document.getElementById("transport-list").innerHTML = list;

    // Event slider bulat
    document.querySelectorAll("[data-alt][data-aspek][data-value]").forEach((el) => {
        el.addEventListener("click", function (event) {
            event.stopPropagation();
            const slug = this.dataset.alt;
            const aspek = this.dataset.aspek;
            const value = Number(this.dataset.value);
            const altIdx = window.transportAlternatif.findIndex(
                (a) => a.nama_transportasi.replace(/\s/g, "") === slug
            );
            if (altIdx !== -1) {
                window.transportAlternatif[altIdx][aspek] = value;
                document.getElementById(
                    `slider-${slug}-${aspek}`
                ).innerHTML = renderSlider(
                    window.transportAlternatif[altIdx].nama_transportasi,
                    aspek,
                    value
                );
                document.querySelectorAll(
                    `#slider-${slug}-${aspek} [data-alt][data-aspek][data-value]`
                ).forEach((newEl) => {
                    newEl.addEventListener("click", arguments.callee, false);
                });
            }
        }, false);
    });

    // Event inner shadow warna
    document.querySelectorAll('.block.mb-5 input[type="checkbox"]').forEach((cb) => {
        cb.addEventListener("change", function () {
            const label = this.closest("label");
            const cardDiv = label.querySelector("div.bg-white");
            cardDiv.classList.remove(
                "shadow-inner-top-red",
                "shadow-inner-top-green",
                "shadow-inner-top-blue",
                "shadow-inner-top-orange",
                "shadow-inner-top-purple"
            );
            if (this.checked) {
                const altNama = this.value;
                const altObj = window.transportAlternatif.find(a => a.id_transportasi == altNama);
                const warna = warnaShadow[altObj?.nama_transportasi] || "";
                if (warna) cardDiv.classList.add(warna.shadow);
            }
        });
    });
}

// ========== Cek Estimasi ===========
function hitungHarga(alt, distance) {
    // Pakai nama_transportasi!
    switch (alt.nama_transportasi) {
        case "Kereta":
            let cost = 3000;
            if (distance > 25) {
                let extra = Math.ceil((distance - 25) / 10) * 1000;
                cost += extra;
            }
            return Math.min(cost, 12000);
        case "Bus":
            return 3500;
        case "Ojek Online":
            return 3500 * distance;
        case "Motor Pribadi":
            return Math.ceil((distance / 14) * 10000);
        case "Mobil Pribadi":
            return Math.ceil((distance / 10) * 13000);
        case "Sepeda":
        case "Jalan Kaki":
            return 0;
        default:
            return 0;
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Fetch data transportasi dari backend, render setelah data masuk!
    fetch('/transportasi')
      .then(res => res.json())
      .then(data => {
          window.transportAlternatif = data.map(item => ({
              ...item,
              harga: item.harga ?? 0, // inisialisasi, bisa disesuaikan jika dari DB
              waktu: item.waktu ?? null,
              kenyamanan: item.kenyamanan ?? 3,
              keamanan: item.keamanan ?? 3,
              aksesbilitas: item.aksesbilitas ?? 3
          }));
          renderTransport();
      });

    // Event cek estimasi jarak & waktu
    document.getElementById("cek_estimasi").onclick = function () {
        const input = document.getElementById("asal_autocomplete");
        const origin = asalLokasi || input.value;
        if (!origin) {
            alert("Isi dulu lokasi asal!");
            return;
        }

        const service = new google.maps.DirectionsService();

        // DRIVING saja untuk jarak utama
        service.route(
            {
                origin: origin,
                destination: tujuan,
                travelMode: "DRIVING",
            },
            function (result, status) {
                if (status === "OK") {
                    const jarakText = result.routes[0].legs[0].distance.text;
                    const waktu = result.routes[0].legs[0].duration.text;
                    document.getElementById("hasil_jarak").innerText = jarakText;
                    document.getElementById("hasil_waktu").innerText = waktu;

                    // Dapatkan jarak numerik (km)
                    let distance = 0;
                    if (jarakText.includes("km")) {
                        distance = parseFloat(jarakText.replace(" km", "").replace(",", "."));
                    } else if (jarakText.includes("m")) {
                        distance = parseFloat(jarakText.replace(" m", "")) / 1000;
                    }

                    // Hitung & update harga ke setiap transportAlternatif
                    window.transportAlternatif.forEach(function (alt) {
                        alt.harga = hitungHarga(alt, distance);
                        let hargaElem = document.getElementById("harga-" + alt.nama_transportasi.replace(/\s/g, ""));
                        if (hargaElem) {
                            hargaElem.innerText = "Rp " + alt.harga.toLocaleString("id-ID");
                        }
                    });
                } else {
                    document.getElementById("hasil_jarak").innerText = "Lokasi tidak ditemukan";
                    document.getElementById("hasil_waktu").innerText = "";
                }
            }
        );

        // Untuk estimasi waktu tiap alternatif transportasi (bisa beda mode)
        window.transportAlternatif.forEach(function (alt) {
            service.route(
                {
                    origin: origin,
                    destination: tujuan,
                    travelMode: alt.mode,
                },
                function (result, status) {
                    let waktuElemen = document.getElementById("waktu-" + alt.nama_transportasi.replace(/\s/g, ""));
                    if (status === "OK") {
                        const waktu = result.routes[0].legs[0].duration.text;
                        waktuElemen.innerText = waktu;
                        alt.waktu = waktu; // update waktu (string)
                    } else {
                        waktuElemen.innerText = "-";
                        alt.waktu = null;
                    }
                }
            );
        });
    };
});

// bobot

document.addEventListener("DOMContentLoaded", function () {
    // ... kode fetch & renderTransport & cek_estimasi kamu

    // KODE SLIDER BOBOT:
    const sliderList = [
        { slider: "slider-biaya", label: "label-biaya" },
        { slider: "slider-waktu", label: "label-waktu" },
        { slider: "slider-keamanan", label: "label-keamanan" },
        { slider: "slider-kenyamanan", label: "label-kenyamanan" },
        { slider: "slider-aksesbilitas", label: "label-aksesbilitas" },
    ];
    sliderList.forEach((item) => {
        const slider = document.getElementById(item.slider);
        const label = document.getElementById(item.label);
        if (slider && label) {
            slider.addEventListener("input", function () {
                label.textContent = slider.value;
            });
        }
    });
});
