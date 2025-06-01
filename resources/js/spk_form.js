// ====== Lokasi & Autocomplete ======
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

// ====== Data Alternatif Transport ======
const tujuan = "Universitas Indonesia, Depok, Indonesia";
window.transportAlternatif = [
    {
        nama: "Kereta",
        icon: "🚆",
        harga: 30000,
        waktu: null,
        mode: "TRANSIT",
        kenyamanan: 4,
        keamanan: 3,
        aksesbilitas: 5,
    },
    {
        nama: "Bus",
        icon: "🚌",
        harga: 15000,
        waktu: null,
        mode: "TRANSIT",
        kenyamanan: 3,
        keamanan: 4,
        aksesbilitas: 4,
    },
    {
        nama: "Ojek Online",
        icon: "🛵",
        harga: 20000,
        waktu: null,
        mode: "TWO_WHEELER",
        kenyamanan: 3,
        keamanan: 3,
        aksesbilitas: 5,
    },
    {
        nama: "Motor Pribadi",
        icon: "🏍️",
        harga: 25000,
        waktu: null,
        mode: "TWO_WHEELER",
        kenyamanan: 3,
        keamanan: 2,
        aksesbilitas: 4,
    },
    {
        nama: "Mobil Pribadi",
        icon: "🚗",
        harga: 40000,
        waktu: null,
        mode: "DRIVING",
        kenyamanan: 5,
        keamanan: 4,
        aksesbilitas: 3,
    },
    {
        nama: "Sepeda",
        icon: "🚲",
        harga: 0,
        waktu: null,
        mode: "BICYCLING",
        kenyamanan: 2,
        keamanan: 2,
        aksesbilitas: 2,
    },
    {
        nama: "Jalan Kaki",
        icon: "🚶",
        harga: 0,
        waktu: null,
        mode: "WALKING",
        kenyamanan: 1,
        keamanan: 1,
        aksesbilitas: 1,
    },
];

const warnaShadow = {
    Kereta: {
        shadow: "shadow-inner-top-strong-orange",
        border: "border-orange-strong",
    }, // Orange
    Bus: {
        shadow: "shadow-inner-top-strong-blue",
        border: "border-blue-strong",
    }, // Biru
    "Ojek Online": {
        shadow: "shadow-inner-top-strong-green",
        border: "border-green-strong",
    }, // Hijau
    "Motor Pribadi": {
        shadow: "shadow-inner-top-strong-red",
        border: "border-red-strong",
    }, // Merah
    "Mobil Pribadi": {
        shadow: "shadow-inner-top-strong-purple",
        border: "border-purple-strong",
    }, // Ungu
    // dst...
};

// ====== Render Alternatif Transport (Vertikal) ======
function renderSlider(namaAlt, aspek, nilai) {
    // namaAlt: "Kereta", aspek: "kenyamanan", nilai: angka 1-5
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
            let slug = alt.nama.replace(/\s/g, "");
            return `
        <label class="block mb-5">
            <input type="checkbox" class="hidden peer" name="transportasi[]" value="${
                alt.nama
            }">
            <div class="bg-white border-2 border-gray-300 rounded-2xl shadow flex flex-row items-center px-8 py-7 max-w-4xl mx-auto cursor-pointer
                transition peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200">
                <!-- ... (lanjutan isi card, sama seperti kode kamu) ... -->
                <!-- Kiri: Icon & Nama -->
                <div class="flex flex-col items-center min-w-[120px]">
                    <span class="text-5xl mb-4">${alt.icon}</span>
                    <span class="text-2xl text-gray-600 font-light">${
                        alt.nama
                    }</span>
                </div>
                <div class="border-l border-gray-300 h-28 mx-8"></div>
                <div class="flex flex-col justify-center min-w-[120px] gap-5">
                    <div>
                        <div class="text-lg text-gray-700 font-semibold">Harga</div>
                        <div class="text-lg text-gray-600 font-light">Rp ${alt.harga.toLocaleString(
                            "id-ID"
                        )}</div>
                    </div>
                    <div>
                        <div class="text-lg text-gray-700 font-semibold">Waktu</div>
                        <div class="text-lg text-gray-600 font-light" id="waktu-${slug}">${
                alt.waktu ? alt.waktu : "-"
            }</div>
                    </div>
                </div>
                <div class="flex-1 grid grid-cols-2 gap-x-12 gap-y-6 pl-8">
                    <div>
                        <div class="text-lg font-semibold text-gray-700 mb-2">Kenyamanan</div>
                        <div class="flex items-center gap-2" id="slider-${slug}-kenyamanan">
                            ${renderSlider(
                                alt.nama,
                                "kenyamanan",
                                alt.kenyamanan
                            )}
                        </div>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-700 mb-2">Aksesbilitas</div>
                        <div class="flex items-center gap-2" id="slider-${slug}-aksesbilitas">
                            ${renderSlider(
                                alt.nama,
                                "aksesbilitas",
                                alt.aksesbilitas
                            )}
                        </div>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-700 mb-2">Keamanan</div>
                        <div class="flex items-center gap-2" id="slider-${slug}-keamanan">
                            ${renderSlider(alt.nama, "keamanan", alt.keamanan)}
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

    // EVENT SLIDER BULAT (TIDAK BERUBAH)
    document
        .querySelectorAll("[data-alt][data-aspek][data-value]")
        .forEach((el) => {
            el.addEventListener(
                "click",
                function (event) {
                    event.stopPropagation();
                    const slug = this.dataset.alt;
                    const aspek = this.dataset.aspek;
                    const value = Number(this.dataset.value);
                    const altIdx = window.transportAlternatif.findIndex(
                        (a) => a.nama.replace(/\s/g, "") === slug
                    );
                    if (altIdx !== -1) {
                        window.transportAlternatif[altIdx][aspek] = value;
                        document.getElementById(
                            `slider-${slug}-${aspek}`
                        ).innerHTML = renderSlider(
                            window.transportAlternatif[altIdx].nama,
                            aspek,
                            value
                        );
                        document
                            .querySelectorAll(
                                `#slider-${slug}-${aspek} [data-alt][data-aspek][data-value]`
                            )
                            .forEach((newEl) => {
                                newEl.addEventListener(
                                    "click",
                                    function (event) {
                                        event.stopPropagation();
                                        const newValue = Number(
                                            this.dataset.value
                                        );
                                        window.transportAlternatif[altIdx][
                                            aspek
                                        ] = newValue;
                                        document.getElementById(
                                            `slider-${slug}-${aspek}`
                                        ).innerHTML = renderSlider(
                                            window.transportAlternatif[altIdx]
                                                .nama,
                                            aspek,
                                            newValue
                                        );
                                        document
                                            .querySelectorAll(
                                                `#slider-${slug}-${aspek} [data-alt][data-aspek][data-value]`
                                            )
                                            .forEach((newerEl) => {
                                                newerEl.addEventListener(
                                                    "click",
                                                    arguments.callee,
                                                    false
                                                );
                                            });
                                    },
                                    false
                                );
                            });
                    }
                },
                false
            );
        });

    // EVENT INNER SHADOW WARNA (Card)
    document
        .querySelectorAll('.block.mb-5 input[type="checkbox"]')
        .forEach((cb) => {
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
                    const warna = warnaShadow[altNama] || "";
                    if (warna) cardDiv.classList.add(warna);
                }
            });
        });
}

renderTransport();

// ====== Estimasi Waktu & Jarak ======
document.getElementById("cek_estimasi").onclick = function () {
    const input = document.getElementById("asal_autocomplete");
    const origin = asalLokasi || input.value;
    if (!origin) {
        alert("Isi dulu lokasi asal!");
        return;
    }

    const service = new google.maps.DirectionsService();

    // Header (DRIVING)
    service.route(
        {
            origin: origin,
            destination: tujuan,
            travelMode: "DRIVING",
        },
        function (result, status) {
            if (status === "OK") {
                const jarak = result.routes[0].legs[0].distance.text;
                const waktu = result.routes[0].legs[0].duration.text;
                document.getElementById("hasil_jarak").innerText = jarak;
                document.getElementById("hasil_waktu").innerText = waktu;
            } else {
                document.getElementById("hasil_jarak").innerText =
                    "Lokasi tidak ditemukan";
                document.getElementById("hasil_waktu").innerText = "";
            }
        }
    );

    // Untuk alternatif
    window.transportAlternatif.forEach(function (alt) {
        service.route(
            {
                origin: origin,
                destination: tujuan,
                travelMode: alt.mode,
            },
            function (result, status) {
                let waktuElemen = document.getElementById(
                    "waktu-" + alt.nama.replace(/\s/g, "")
                );
                if (status === "OK") {
                    const waktu = result.routes[0].legs[0].duration.text;
                    waktuElemen.innerText = waktu;
                } else {
                    waktuElemen.innerText = "-";
                }
            }
        );
    });
};


// bobot
const sliderList = [{
                slider: 'slider-biaya',
                label: 'label-biaya'
            },
            {
                slider: 'slider-waktu',
                label: 'label-waktu'
            },
            {
                slider: 'slider-keamanan',
                label: 'label-keamanan'
            },
            {
                slider: 'slider-kenyamanan',
                label: 'label-kenyamanan'
            },
            {
                slider: 'slider-aksesbilitas',
                label: 'label-aksesbilitas'
            }
        ];

        sliderList.forEach(item => {
            const slider = document.getElementById(item.slider);
            const label = document.getElementById(item.label);
            if (slider && label) {
                slider.addEventListener('input', function() {
                    label.textContent = slider.value;
                    // label.style.left = (slider.value / slider.max * 100) + "%"; // opsional kalau ingin posisi label mengikuti thumb
                });
            }
        });