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

const tujuan = "Politeknik Negeri Jakarta";
window.transportAlternatif = []; // Akan diisi lewat fetch!

const cardColors = ["#007BFF", "#FFA726", "#66BB6A", "#BA68C8", "#EF5350"];

function getCardColor(index) {
    // Untuk random:
    // return cardColors[Math.floor(Math.random() * cardColors.length)];
    // Untuk urut:
    return cardColors[index % cardColors.length];
}

function renderTransport() {
    const list = window.transportAlternatif
        .map((alt, idx) => {
            let slug = alt.nama_transportasi.replace(/\s/g, "");
            const activeColor = getCardColor(idx);

            return `
            <label class="block mb-5">
                <input type="checkbox" class="hidden peer" name="transportasi[]" value="${
                    alt.id_transportasi
                }">
                <div 
                    class="rounded-2xl border-2 border-gray-300 shadow-md
                        bg-white flex flex-row items-center px-8 py-7 max-w-4xl mx-auto cursor-pointer
                        transition
                        peer-checked:border-[${activeColor}]
                        peer-checked:shadow-[0_0_16px_0_${activeColor}44]
                    ">
                    <!-- KIRI: ICON & NAMA -->
                    <div class="flex flex-col items-center min-w-[120px]">
                        <span class="text-5xl mb-4 text-gray-400 peer-checked:text-[${activeColor}] transition">${
                alt.icon
            }</span>
                        <span class="text-3xl tracking-wider font-light text-gray-700 peer-checked:text-[${activeColor}] transition">${
                alt.nama_transportasi
            }</span>
                    </div>
                    <div class="border-l border-gray-300 h-28 mx-8"></div>
                    <!-- TENGAH: HARGA/WAKTU -->
                    <div class="flex flex-col justify-center min-w-[120px] gap-5">
                        <div>
                            <div class="text-xl font-semibold text-gray-600">Harga</div>
                            <div class="text-lg text-gray-700 font-light" id="harga-${slug}">Rp ${alt.harga.toLocaleString(
                "id-ID"
            )}</div>
                        </div>
                        <div>
                            <div class="text-xl font-semibold text-gray-600">Waktu</div>
                            <div class="text-lg text-gray-700 font-light" id="waktu-${slug}">${
                alt.waktu ? alt.waktu : "-"
            }</div>
                        </div>
                    </div>
                    <div class="flex-1 grid grid-cols-2 gap-x-12 gap-y-6 pl-8">
                        <div>
                            <div class="text-lg font-semibold text-gray-700 mb-2">Kenyamanan</div>
                            <div class="flex items-center gap-2" id="slider-${slug}-kenyamanan">
                                ${renderSlider(
                                    alt.nama_transportasi,
                                    "kenyamanan",
                                    alt.kenyamanan
                                )}
                            </div>
                        </div>
                        <div>
                            <div class="text-lg font-semibold text-gray-700 mb-2">Aksesbilitas</div>
                            <div class="flex items-center gap-2" id="slider-${slug}-aksesbilitas">
                                ${renderSlider(
                                    alt.nama_transportasi,
                                    "aksesbilitas",
                                    alt.aksesbilitas
                                )}
                            </div>
                        </div>
                        <div>
                            <div class="text-lg font-semibold text-gray-700 mb-2">Keamanan</div>
                            <div class="flex items-center gap-2" id="slider-${slug}-keamanan">
                                ${renderSlider(
                                    alt.nama_transportasi,
                                    "keamanan",
                                    alt.keamanan
                                )}
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

    // SLIDER input[type=range] update nilai
    document
        .querySelectorAll("input[type='range'][data-alt][data-aspek]")
        .forEach((el) => {
            el.addEventListener("input", function () {
                const slug = this.dataset.alt;
                const aspek = this.dataset.aspek;
                const value = Number(this.value);
                const altIdx = window.transportAlternatif.findIndex(
                    (a) => a.nama_transportasi.replace(/\s/g, "") === slug
                );
                if (altIdx !== -1) {
                    window.transportAlternatif[altIdx][aspek] = value;
                    document.getElementById(
                        `value-${slug}-${aspek}`
                    ).innerText = value;
                }
            });
        });
}

// Slider abu2
function renderSlider(namaAlt, aspek, nilai) {
    let slug = namaAlt.replace(/\s/g, "");
    return `
        <input
            type="range"
            min="1"
            max="5"
            value="${nilai}"
            step="1"
            class="w-40 slider-transport"
            data-alt="${slug}"
            data-aspek="${aspek}"
            id="range-${slug}-${aspek}"
        >
        <span class="ml-2 font-bold text-gray-400" id="value-${slug}-${aspek}">${nilai}</span>
    `;
}

function activateTransportCardDynamicColor() {
    document.querySelectorAll('.block.mb-5 input[type="checkbox"]').forEach((cb, idx) => {
        const label = cb.closest("label");
        const cardDiv = label.querySelector("div.rounded-2xl");
        const nameSpan = cardDiv.querySelector('span.text-3xl');
        const iconSpan = cardDiv.querySelector('span.text-5xl');
        const sliders = cardDiv.querySelectorAll('.slider-transport');
        const activeColor = getCardColor(idx);

        function applyColor(isChecked) {
            cardDiv.style.borderColor = isChecked ? activeColor : '#D1D5DB';
            cardDiv.style.boxShadow = isChecked ? `0 0 16px 0 ${activeColor}66` : '';
            nameSpan.style.color = isChecked ? activeColor : '#374151';
            iconSpan.style.color = isChecked ? activeColor : '#9CA3AF';
            sliders.forEach(sl => {
                sl.style.accentColor = isChecked ? activeColor : '#d1d5db';
            });
        }

        // Set initial color
        applyColor(cb.checked);

        cb.addEventListener("change", function () {
            applyColor(cb.checked);
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

// transportasi
document.addEventListener("DOMContentLoaded", function () {
    // Fetch data transportasi dari backend, render setelah data masuk!
    fetch("/transportasi")
        .then((res) => res.json())
        .then((data) => {
            window.transportAlternatif = data.map((item) => ({
                ...item,
                harga: item.harga ?? 0, // inisialisasi, bisa disesuaikan jika dari DB
                waktu: item.waktu ?? null,
                keamanan: item.keamanan ?? 3,
                kenyamanan: item.kenyamanan ?? 3,
                aksesbilitas: item.aksesbilitas ?? 3,
            }));
            renderTransport();
            activateTransportCardDynamicColor();
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
                    document.getElementById("hasil_jarak").innerText =
                        jarakText;
                    document.getElementById("hasil_waktu").innerText = waktu;

                    // Dapatkan jarak numerik (km)
                    let distance = 0;
                    if (jarakText.includes("km")) {
                        distance = parseFloat(
                            jarakText.replace(" km", "").replace(",", ".")
                        );
                    } else if (jarakText.includes("m")) {
                        distance =
                            parseFloat(jarakText.replace(" m", "")) / 1000;
                    }

                    // Hitung & update harga ke setiap transportAlternatif
                    window.transportAlternatif.forEach(function (alt) {
                        alt.harga = hitungHarga(alt, distance);
                        let hargaElem = document.getElementById(
                            "harga-" + alt.nama_transportasi.replace(/\s/g, "")
                        );
                        if (hargaElem) {
                            hargaElem.innerText =
                                "Rp " + alt.harga.toLocaleString("id-ID");
                        }
                    });
                } else {
                    document.getElementById("hasil_jarak").innerText =
                        "Lokasi tidak ditemukan";
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
                    let waktuElemen = document.getElementById(
                        "waktu-" + alt.nama_transportasi.replace(/\s/g, "")
                    );
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

// bobot angka slider

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
