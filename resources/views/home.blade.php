<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>UniTrans - Find Your Perfect Transportation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- @vite('resources/css/app.css') <!-- jika pakai laravel vite dan tailwind --> --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen flex items-center">
    <!-- Branding -->
    <div class="fixed top-10 px-10 text-xl font-bold text-gray-900 flex justify-between w-full">
        <div>Unitrans</div>
        @if (session('mahasiswa_id'))
            <span class="px-6 py-2 rounded-lg bg-blue-50 text-blue-700 font-semibold shadow border border-blue-100">
                Halo, {{ session('mahasiswa_nama') }}!
            </span>
        @else
            <a href="{{ route('login-mahasiswa') }}"
                class="px-6 py-2 rounded-lg border-2 border-blue-600 text-blue-600 font-semibold hover:bg-blue-600 hover:text-white transition">
                Login
            </a>
        @endif
    </div>


    <!--  -->
    <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-8 py-16 w-4/5">
        <!-- Kiri: Text dan CTA -->
        <div class="w-full md:w-1/2 space-y-6">
            <h1 class="text-4xl md:text-5xl font-light text-gray-700 leading-snug">
                Find Your Perfect <br>
                <span class="font-bold text-blue-600">Transportation</span>
            </h1>
            <p class="text-gray-500 text-lg mr-10 text-justify">Pilih transportasi ke kampus dengan lebih mudah dan
                sesuai kebutuhanmu.</p>
            <a href="{{ route('spk.form') }}"
                class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition font-semibold text-lg">
                <p class="mr-3">Mulai Sekarang</p>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-bus">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M18 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M4 17h-2v-11a1 1 0 0 1 1 -1h14a5 7 0 0 1 5 7v5h-2m-4 0h-8" />
                    <path d="M16 5l1.5 7l4.5 0" />
                    <path d="M2 10l15 0" />
                    <path d="M7 5l0 5" />
                    <path d="M12 5l0 5" />
                </svg>
            </a>
            <!-- Info Keterangan -->
            <div class="flex items-start mt-8">
                <span class="mt-1 text-orange-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </span>
                <p class="ml-3 mr-10 text-gray-600 text-base text-justify">
                    Pilih transportasi ke kampus dengan lebih mudah dan tepat. Sistem ini membantumu membandingkan
                    berbagai pilihan transportasi dengan akurat.
                </p>
            </div>
        </div>
        <!-- Kanan: Gambar -->
        <div class="hidden md:flex w-full md:w-2/3 justify-center">
            <div class="relative">
                <img src="{{ asset('images/homeImage.png') }}" alt="Kereta">
            </div>
        </div>
    </div>

</body>

</html>
