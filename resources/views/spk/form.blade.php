<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Lokasi dengan Google Places</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Maps Places API -->

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>

    {{-- <style>
        /* Contoh untuk orange, ganti sesuai warna tiap transportasi */
        .shadow-inner-top-strong-orange {
            box-shadow: inset 0 8px 32px 0 #FFA726cc !important;
            /* #FFA726cc = orange, opacity 80% */
        }

        .border-orange-strong {
            border-color: #FFA726 !important;
        }

        .shadow-inner-top-strong-red {
            box-shadow: inset 0 8px 32px 0 #EF5350cc !important;
        }

        .border-red-strong {
            border-color: #EF5350 !important;
        }

        /* Tambahkan untuk warna lain sesuai kebutuhan */
        .shadow-inner-top-strong-green {
            box-shadow: inset 0 8px 32px 0 #66BB6Acc !important;
        }

        .border-green-strong {
            border-color: #66BB6A !important;
        }

        .shadow-inner-top-strong-blue {
            box-shadow: inset 0 8px 32px 0 #007BFFcc !important;
        }

        .border-blue-strong {
            border-color: #007BFF !important;
        }

        .shadow-inner-top-strong-purple {
            box-shadow: inset 0 8px 32px 0 #BA68C8cc !important;
        }

        .border-purple-strong {
            border-color: #BA68C8 !important;
        }
    </style> --}}


</head>

<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-start">

    <!-- SECTION: Search Lokasi (Tetap Flex-row, Card) -->
    <div class="container mx-auto py-10">
        <div class="max-w-4xl mx-auto">
            <div
                class="bg-white border-2 border-gray-200 rounded-3xl px-10 py-8 shadow flex flex-col md:flex-row items-center gap-8">
                <!-- Ikon Kiri -->
                <div class="flex-shrink-0 flex items-center justify-center">
                    <img src=" {{ asset('images/maps-logo.png') }} " alt="Google Maps"
                        class="h-20 w-20 object-contain" />
                </div>
                <!-- Tengah: Input & Hasil -->
                <div class="flex-1 w-full min-w-0">
                    <div class="font-bold text-2xl mb-1 tracking-wide text-gray-900">Lokasimu</div>
                    <input id="asal_autocomplete" type="text"
                        class="w-full border border-gray-300 rounded-xl px-5 py-3 text-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                        placeholder="Masukkan lokasi kamu..." autocomplete="off">
                    <div class="mt-3 text-3xl text-black font-light">
                        <span id="hasil_jarak">0 km</span>
                        <span id="hasil_waktu" class="ml-3 text-gray-500 text-md"></span>
                    </div>
                </div>
                <!-- Tombol -->
                <div class="flex items-center justify-center w-full md:w-auto">
                    <button id="cek_estimasi" type="button"
                        class="bg-blue-500 text-white px-8 py-3 rounded-2xl font-semibold shadow hover:bg-blue-600 text-md transition">
                        Cek Estimasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION: Alternatif Transportasi (Vertikal/Satu Kolom) -->
    <section class="w-full max-w-4xl mx-auto mt-8">
        <div class="bg-white border-2 border-gray-200 rounded-3xl px-8 py-8 shadow">
            <div class="text-2xl font-bold mb-7 text-center">Alternatif Transportasi</div>
            <div id="transport-list" class="space-y-7"></div>
        </div>
    </section>

    {{-- bobot --}}
    <section class="w-full mt-16 bg-white rounded-2xl border-2 border-gray-200 shadow p-10 max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-8 tracking-wider">Faktor Penilaian</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-10 gap-y-14">
            <!-- Biaya Perjalanan -->
            <div class="flex flex-col items-center">
                <div class="text-gray-400 text-lg mb-2">Biaya Perjalanan</div>
                <div class="relative flex items-center justify-center w-full mb-2" style="height: 36px;">
                    <span id="label-biaya"
                        class="absolute left-1/2 -translate-x-1/2 -top-7 px-3 py-1 rounded-lg text-white text-base font-semibold bg-blue-500">78</span>
                    <input id="slider-biaya" type="range" min="0" max="100" value="78"
                        class="w-full h-2 rounded-lg appearance-none bg-gradient-to-r from-blue-500 to-blue-200 focus:outline-none outline-none transition-all
          [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-8 [&::-webkit-slider-thumb]:h-8
          [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:shadow-lg" />
                </div>
            </div>
            <!-- Waktu Tempuh -->
            <div class="flex flex-col items-center">
                <div class="text-gray-400 text-lg mb-2">Waktu Tempuh</div>
                <div class="relative flex items-center justify-center w-full mb-2" style="height: 36px;">
                    <span id="label-waktu"
                        class="absolute left-1/2 -translate-x-1/2 -top-7 px-3 py-1 rounded-lg text-white text-base font-semibold bg-purple-400">78</span>
                    <input id="slider-waktu" type="range" min="0" max="100" value="78"
                        class="w-full h-2 rounded-lg appearance-none bg-gradient-to-r from-purple-500 to-purple-200 focus:outline-none outline-none transition-all
          [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-8 [&::-webkit-slider-thumb]:h-8
          [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:shadow-lg" />
                </div>
            </div>
            <!-- Keamanan -->
            <div class="flex flex-col items-center">
                <div class="text-gray-400 text-lg mb-2 flex items-center gap-1">
                    Keamanan
                    <svg width="18" height="18" class="inline" fill="none">
                        <circle cx="9" cy="9" r="8" stroke="#F44336" stroke-width="2" /><text x="7"
                            y="13" font-size="10" fill="#F44336">i</text>
                    </svg>
                </div>
                <div class="relative flex items-center justify-center w-full mb-2" style="height: 36px;">
                    <span id="label-keamanan"
                        class="absolute left-1/2 -translate-x-1/2 -top-7 px-3 py-1 rounded-lg text-white text-base font-semibold bg-red-400">78</span>
                    <input id="slider-keamanan" type="range" min="0" max="100" value="78"
                        class="w-full h-2 rounded-lg appearance-none bg-gradient-to-r from-red-400 to-red-200 focus:outline-none outline-none transition-all
          [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-8 [&::-webkit-slider-thumb]:h-8
          [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:shadow-lg" />
                </div>
            </div>
            <!-- Kenyamanan -->
            <div class="flex flex-col items-center">
                <div class="text-gray-400 text-lg mb-2">Kenyamanan</div>
                <div class="relative flex items-center justify-center w-full mb-2" style="height: 36px;">
                    <span id="label-kenyamanan"
                        class="absolute left-1/2 -translate-x-1/2 -top-7 px-3 py-1 rounded-lg text-white text-base font-semibold bg-orange-400">78</span>
                    <input id="slider-kenyamanan" type="range" min="0" max="100" value="78"
                        class="w-full h-2 rounded-lg appearance-none bg-gradient-to-r from-orange-400 to-orange-200 focus:outline-none outline-none transition-all
          [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-8 [&::-webkit-slider-thumb]:h-8
          [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:shadow-lg" />
                </div>
            </div>
            <!-- Aksesbilitas -->
            <div class="flex flex-col items-center">
                <div class="text-gray-400 text-lg mb-2">Aksesbilitas</div>
                <div class="relative flex items-center justify-center w-full mb-2" style="height: 36px;">
                    <span id="label-aksesbilitas"
                        class="absolute left-1/2 -translate-x-1/2 -top-7 px-3 py-1 rounded-lg text-white text-base font-semibold bg-green-500">78</span>
                    <input id="slider-aksesbilitas" type="range" min="0" max="100" value="78"
                        class="w-full h-2 rounded-lg appearance-none bg-gradient-to-r from-green-500 to-green-200 focus:outline-none outline-none transition-all
          [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-8 [&::-webkit-slider-thumb]:h-8
          [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:shadow-lg" />
                </div>
            </div>
        </div>
    </section>
    <div class="flex justify-center my-10">
        <button id="cek_rekomendasi"
            class="bg-green-600 text-white px-8 py-3 rounded-2xl font-semibold shadow hover:bg-green-700 text-lg transition">
            Cek Rekomendasi Transportasi
        </button>
    </div>
    <div id="hasil-rekomendasi" class="my-10 text-center"></div>



    <script src="{{ asset('js/spk_form.js') }}"></script>
    <script src="{{ asset('js/perhitungan.js') }}"></script>

</body>

</html>
