<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Lokasi dengan Google Places</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Maps Places API -->

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>

    <style>
        .slider-transport {
            accent-color: #d1d5db;
            /* gray-300 default */
            height: 32px;
            /* Tinggi area agar thumb tidak terpotong */
        }
    </style>


</head>

<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-start">
    <!-- Branding -->
    <div class="fixed top-10 px-10 text-xl font-bold text-gray-900 flex justify-between w-full">
        <a href="{{ route('home') }}">Unitrans</a>
        @if (session('mahasiswa_id'))
            <a href="{{ route('profile') }}"
                class="px-6 py-2 rounded-lg bg-blue-50 text-blue-700 font-semibold shadow border border-blue-100">
                Halo, {{ session('mahasiswa_nama') }}!
            </a>
        @else
            <a href="{{ route('login-mahasiswa') }}"
                class="px-6 py-2 rounded-lg border-2 border-blue-600 text-blue-600 font-semibold hover:bg-blue-600 hover:text-white transition">
                Login
            </a>
        @endif
    </div>

    <!-- SECTION: Search Lokasi (Tetap Flex-row, Card) -->
    <div class="container mx-auto pt-24">
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
    <section class="w-full mt-16 bg-white rounded-2xl border-2 border-gray-200 shadow px-8 py-14 max-w-4xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-14 tracking-wider">Faktor Penilaian</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-12 gap-y-20">

            <!-- BIAYA -->
            <div class="flex flex-col items-center pt-2 pb-2">
                <div class="text-lg mb-10 font-semibold" style="color:#007BFF">Biaya Perjalanan</div>
                <div class="relative flex items-center justify-center w-full mb-2" style="height: 52px;">
                    <span id="label-biaya"
                        class="absolute left-1/2 -translate-x-1/2 -top-9 px-4 py-1.5 rounded-lg text-white text-base font-bold shadow-md select-none"
                        style="background:#007BFF">1</span>
                    <input id="slider-biaya" type="range" min="1" max="100" value="1" class="w-full"
                        style="accent-color: #007BFF;">
                </div>
            </div>

            <!-- WAKTU -->
            <div class="flex flex-col items-center pt-2 pb-2">
                <div class="text-lg mb-10 font-semibold" style="color:#BA68C8">Waktu Tempuh</div>
                <div class="relative flex items-center justify-center w-full mb-2" style="height: 52px;">
                    <span id="label-waktu"
                        class="absolute left-1/2 -translate-x-1/2 -top-9 px-4 py-1.5 rounded-lg text-white text-base font-bold shadow-md select-none"
                        style="background:#BA68C8">1</span>
                    <input id="slider-waktu" type="range" min="1" max="100" value="1" class="w-full"
                        style="accent-color: #BA68C8;">
                </div>
            </div>

            <!-- KEAMANAN -->
            <div class="flex flex-col items-center pt-2 pb-2">
                <div class="text-lg mb-10 font-semibold" style="color:#EF5350">Keamanan</div>
                <div class="relative flex items-center justify-center w-full mb-2" style="height: 52px;">
                    <span id="label-keamanan"
                        class="absolute left-1/2 -translate-x-1/2 -top-9 px-4 py-1.5 rounded-lg text-white text-base font-bold shadow-md select-none"
                        style="background:#EF5350">1</span>
                    <input id="slider-keamanan" type="range" min="1" max="100" value="1"
                        class="w-full" style="accent-color: #EF5350;">
                </div>
            </div>

            <!-- KENYAMANAN -->
            <div class="flex flex-col items-center pt-2 pb-2">
                <div class="text-lg mb-10 font-semibold" style="color:#FFA726">Kenyamanan</div>
                <div class="relative flex items-center justify-center w-full mb-2" style="height: 52px;">
                    <span id="label-kenyamanan"
                        class="absolute left-1/2 -translate-x-1/2 -top-9 px-4 py-1.5 rounded-lg text-white text-base font-bold shadow-md select-none"
                        style="background:#FFA726">1</span>
                    <input id="slider-kenyamanan" type="range" min="1" max="100" value="1"
                        class="w-full" style="accent-color: #FFA726;">
                </div>
            </div>

            <!-- AKSESBILITAS -->
            <div class="flex flex-col items-center pt-2 pb-2">
                <div class="text-lg mb-10 font-semibold" style="color:#66BB6A">Aksesbilitas</div>
                <div class="relative flex items-center justify-center w-full mb-2" style="height: 52px;">
                    <span id="label-aksesbilitas"
                        class="absolute left-1/2 -translate-x-1/2 -top-9 px-4 py-1.5 rounded-lg text-white text-base font-bold shadow-md select-none"
                        style="background:#66BB6A">1</span>
                    <input id="slider-aksesbilitas" type="range" min="1" max="100" value="1"
                        class="w-full" style="accent-color: #66BB6A;">
                </div>
            </div>
            <div class="hidden md:block"></div>
        </div>
    </section>


    <div class="flex justify-center my-10">
        <button id="cek_rekomendasi"
            class="bg-green-600 text-white px-8 py-3 rounded-2xl font-semibold shadow hover:bg-green-700 text-lg transition">
            Cek Rekomendasi Transportasi
        </button>
    </div>


    <script src="{{ asset('js/spk_form.js') }}"></script>
    <script src="{{ asset('js/perhitungan.js') }}"></script>

</body>

</html>
