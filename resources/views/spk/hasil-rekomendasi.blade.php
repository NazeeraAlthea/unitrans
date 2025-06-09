<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Rekomendasi Teratas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen font-sans">
    <div class="max-w-4xl mx-auto py-12 px-4 bg-white rounded-3xl shadow-lg">
        <h1 class="text-3xl md:text-4xl font-bold text-center mb-10 tracking-wide">🏆 Hasil Rekomendasi Teratas 🏆</h1>

        @php
            $juara1 = $ranking[0] ?? null;
            $juara2 = $ranking[1] ?? null;
            $juara3 = $ranking[2] ?? null;

            function waktuFormat($menit)
            {
                if ($menit === null || $menit === '-' || $menit == 0) {
                    return '-';
                }
                $jam = floor($menit / 60);
                $mnt = $menit % 60;
                if ($jam > 0) {
                    return $jam . ' jam ' . ($mnt > 0 ? $mnt . ' menit' : '');
                } else {
                    return $mnt . ' menit';
                }
            }
        @endphp

        @if ($juara1)
            <div class="flex flex-col items-center mb-12">
                <div class="relative group">
                    
                    <div class="absolute -top-7 left-1/2 -translate-x-1/2 z-10">
                        <span
                            class="inline-block bg-gradient-to-r from-yellow-400 to-yellow-200 text-yellow-900 font-black px-4 py-2 rounded-full text-lg shadow-lg border-2 border-yellow-500 animate-bounce">
                            #1 TERBAIK
                        </span>
                    </div>
                    <div
                        class="w-[350px] md:w-[420px] min-h-[160px] rounded-3xl border-4 border-yellow-400 bg-gradient-to-b from-yellow-50 via-white to-yellow-100 shadow-2xl flex flex-col md:flex-row items-center justify-center p-6 gap-7 hover:scale-105 transition-transform duration-300">
                        <!-- Trophy -->
                        <!-- Icon -->
                        <div
                            class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-gradient-to-tr from-yellow-200 via-white to-yellow-100 border-4 border-yellow-300 flex items-center justify-center shadow-inner text-6xl">
                            {{ $juara1['icon'] ?? '🚌' }}
                        </div>
                        <div class="font-bold text-2xl mb-1">{{ $juara1['nama'] }}</div>
                        <!-- Detail -->
                        <div class="flex-1 text-center md:text-left">
                            <div class="flex flex-wrap justify-center md:justify-start gap-3 text-base mb-3">
                                <span class="px-2 py-1 bg-gray-100 rounded-xl font-semibold shadow-sm">
                                    Harga: <span
                                        class="text-green-600">Rp{{ number_format($juara1['detail']['harga'] ?? 0, 0, ',', '.') }}</span>
                                </span>
                                <span class="px-2 py-1 bg-gray-100 rounded-xl font-semibold shadow-sm">
                                    Waktu: <span
                                        class="text-blue-600">{{ waktuFormat($juara1['detail']['waktu'] ?? '-') }}</span>
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2 text-sm md:text-base">
                                <span
                                    class="bg-orange-100 px-3 py-1 rounded-lg shadow text-orange-700 font-semibold">Keamanan:
                                    {{ $juara1['detail']['keamanan'] ?? '-' }}</span>
                                <span
                                    class="bg-green-100 px-3 py-1 rounded-lg shadow text-green-700 font-semibold">Kenyamanan:
                                    {{ $juara1['detail']['kenyamanan'] ?? '-' }}</span>
                                <span
                                    class="bg-blue-100 px-3 py-1 rounded-lg shadow text-blue-700 font-semibold">Aksesibilitas:
                                    {{ $juara1['detail']['aksesbilitas'] ?? '-' }}</span>
                            </div>
                            <div class="text-center mt-3">
                                <span
                                    class="inline-block bg-gradient-to-r from-yellow-300 to-yellow-100 text-lg font-bold px-5 py-2 rounded-xl shadow border-2 border-yellow-300">{{ number_format($juara1['skor']) }}/100</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex flex-row items-end justify-center gap-8 mb-12">
            @if ($juara2)
                <div class="flex flex-col items-center group transition-transform duration-300 hover:scale-105">
                    <div class="relative mb-2">
                        <span
                            class="absolute -top-8 left-1/2 -translate-x-1/2 text-5xl text-gray-400 font-black opacity-60 select-none pointer-events-none">2</span>
                        <div
                            class="w-20 h-20 rounded-full bg-gradient-to-t from-gray-200 via-gray-100 to-white flex items-center justify-center border-4 border-gray-300 shadow-md text-4xl">
                            {{ $juara2['icon'] ?? '🚌' }}
                        </div>
                    </div>
                    <div class="font-semibold mt-2 text-lg">{{ $juara2['nama'] }}</div>
                    <div class="text-gray-500 font-bold">{{ number_format($juara2['skor']) }}/100</div>
                </div>
            @endif
            @if ($juara3)
                <div class="flex flex-col items-center group transition-transform duration-300 hover:scale-105">
                    <div class="relative mb-2">
                        <span
                            class="absolute -top-8 left-1/2 -translate-x-1/2 text-5xl text-orange-300 font-black opacity-70 select-none pointer-events-none">3</span>
                        <div
                            class="w-20 h-20 rounded-full bg-gradient-to-t from-orange-100 via-white to-white flex items-center justify-center border-4 border-orange-200 shadow-md text-4xl">
                            {{ $juara3['icon'] ?? '🚗' }}
                        </div>
                    </div>
                    <div class="font-semibold mt-2 text-lg">{{ $juara3['nama'] }}</div>
                    <div class="text-gray-500 font-bold">{{ number_format($juara3['skor']) }}/100</div>
                </div>
            @endif
        </div>


        <!-- Garis Pembatas -->
        <div class="border-t-2 border-gray-200 my-6"></div>

        <!-- Bobot Kriteria -->
        <div class="text-center text-lg font-semibold mb-4">Bobot yang diterapkan</div>
        <div class="flex flex-wrap justify-center gap-4 my-10">
            @foreach ($bobot as $bk)
                @php
                    // Pilih warna background dan text sesuai kriteria
                    $warna = match ($bk['nama_kriteria']) {
                        'harga' => 'bg-blue-50 text-blue-700 ring-2 ring-blue-200',
                        'waktu' => 'bg-purple-50 text-purple-700 ring-2 ring-purple-200',
                        'keamanan' => 'bg-red-50 text-red-700 ring-2 ring-red-200',
                        'kenyamanan' => 'bg-yellow-50 text-yellow-700 ring-2 ring-yellow-200',
                        'aksesbilitas' => 'bg-green-50 text-green-700 ring-2 ring-green-200',
                        default => 'bg-gray-50 text-gray-700 ring-2 ring-gray-200',
                    };
                    // Pilih ikon
                    $ikon = match ($bk['nama_kriteria']) {
                        'harga' => '💸',
                        'waktu' => '⏰',
                        'keamanan' => '🛡️',
                        'kenyamanan' => '🛋️',
                        'aksesbilitas' => '♿',
                        default => '🔹',
                    };
                @endphp
                <div
                    class="flex items-center px-5 py-3 rounded-2xl shadow-lg font-semibold text-lg {{ $warna }} transition-all duration-150 min-w-[170px] justify-center">
                    <span class="mr-2 text-2xl">{{ $ikon }}</span>
                    <span class="tracking-wide">{{ ucfirst($bk['nama_kriteria']) }}</span>
                    <span
                        class="ml-3 text-xl font-extrabold @if ($bk['nama_kriteria'] == 'harga') text-blue-500 @elseif($bk['nama_kriteria'] == 'waktu') text-purple-500 @elseif($bk['nama_kriteria'] == 'keamanan') text-red-500 @elseif($bk['nama_kriteria'] == 'kenyamanan') text-yellow-500 @elseif($bk['nama_kriteria'] == 'aksesbilitas') text-green-600 @endif">
                        {{ round($bk['bobot'] * 100) }}%
                    </span>
                </div>
            @endforeach
        </div>

        <!-- Tambahan Table Penilaian Lengkap -->

        {{-- MATRICS ALTERNATIF --}}
        <div class="max-w-6xl mx-auto my-12">
            <div class="bg-white rounded-3xl shadow-xl px-8 py-6 mb-10 border-2 border-blue-100">
                <h2 class="text-xl font-bold mb-6 text-blue-600 flex items-center gap-2">
                    <span class="inline-block w-2 h-8 rounded bg-blue-400 mr-2"></span>
                    Matriks Alternatif
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full rounded-2xl overflow-hidden shadow border border-blue-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-4 py-2 font-bold text-gray-600 text-left">Transportasi</th>
                                @foreach ($kriteriaArr as $kr)
                                    <th class="px-4 py-2 font-bold text-blue-700">{{ ucfirst($kr) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nilaiAlternatif as $row)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-4 py-2 font-semibold flex items-center gap-2">
                                        <span class="text-2xl">{{ $transportasi[$loop->index]['icon'] ?? '🚌' }}</span>
                                        {{ $row['nama'] }}
                                    </td>
                                    @foreach ($kriteriaArr as $kr)
                                        <td class="px-4 py-2">{{ $row[$kr] }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- NORMALISASI --}}
        <div class="max-w-4xl mx-auto my-12">
            <div class="bg-white rounded-3xl shadow-xl px-8 py-6 mb-10 border-2 border-purple-100">
                <h2 class="text-xl font-bold mb-6 text-purple-600 flex items-center gap-2">
                    <span class="inline-block w-2 h-8 rounded bg-purple-400 mr-2"></span>
                    Tabel Normalisasi
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full rounded-2xl overflow-hidden shadow border border-purple-200">
                        <thead class="bg-purple-50">
                            <tr>
                                <th class="px-4 py-2 font-bold text-gray-600 text-left">Alternatif</th>
                                @foreach ($kriteriaArr as $kr)
                                    <th class="px-4 py-2 font-bold text-purple-700">{{ ucfirst($kr) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transportasi as $idx => $alt)
                                <tr class="hover:bg-purple-50 transition">
                                    <td class="px-4 py-2 font-semibold flex items-center gap-2">
                                        <span class="text-2xl">{{ $alt['icon'] ?? '🚌' }}</span>
                                        {{ $alt['nama'] }}
                                    </td>
                                    @foreach ($normalisasi[$idx] as $v)
                                        <td class="px-4 py-2">{{ number_format($v, 4) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MATRIKS BERBOBOT --}}
        <div class="max-w-4xl mx-auto my-12">
            <div class="bg-white rounded-3xl shadow-xl px-8 py-6 mb-10 border-2 border-green-100">
                <h2 class="text-xl font-bold mb-6 text-green-600 flex items-center gap-2">
                    <span class="inline-block w-2 h-8 rounded bg-green-400 mr-2"></span>
                    Matriks Berbobot
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full rounded-2xl overflow-hidden shadow border border-green-200">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-4 py-2 font-bold text-gray-600 text-left">Alternatif</th>
                                @foreach ($kriteriaArr as $kr)
                                    <th class="px-4 py-2 font-bold text-green-700">{{ ucfirst($kr) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transportasi as $idx => $alt)
                                <tr class="hover:bg-green-50 transition">
                                    <td class="px-4 py-2 font-semibold flex items-center gap-2">
                                        <span class="text-2xl">{{ $alt['icon'] ?? '🚌' }}</span>
                                        {{ $alt['nama'] }}
                                    </td>
                                    @foreach ($berbobot[$idx] as $v)
                                        <td class="px-4 py-2">{{ number_format($v, 4) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- PERHITUNGAN AKHIR --}}
        <div class="max-w-4xl mx-auto my-12">
            <div class="bg-white rounded-3xl shadow-xl px-8 py-6 border-2 border-orange-200">
                <h2 class="text-xl font-bold mb-6 text-orange-600 flex items-center gap-2">
                    <span class="inline-block w-2 h-8 rounded bg-orange-400 mr-2"></span>
                    Tabel Perhitungan Akhir COPRAS
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full rounded-2xl overflow-hidden shadow border border-orange-200">
                        <thead class="bg-orange-50">
                            <tr>
                                <th class="px-4 py-2 font-bold text-gray-600 text-left">Alternatif</th>
                                <th class="px-4 py-2 font-bold text-orange-700">S+</th>
                                <th class="px-4 py-2 font-bold text-orange-700">S-</th>
                                <th class="px-4 py-2 font-bold text-orange-700">Qi</th>
                                <th class="px-4 py-2 font-bold text-orange-700">Ui</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transportasi as $idx => $alt)
                                <tr class="hover:bg-orange-50 transition">
                                    <td class="px-4 py-2 font-semibold flex items-center gap-2">
                                        <span class="text-2xl">{{ $alt['icon'] ?? '🚌' }}</span>
                                        {{ $alt['nama'] }}
                                    </td>
                                    <td class="px-4 py-2">{{ number_format($Splus[$idx], 4) }}</td>
                                    <td class="px-4 py-2">{{ number_format($Smin[$idx], 4) }}</td>
                                    <td class="px-4 py-2">{{ number_format($Qi[$idx], 4) }}</td>
                                    <td class="px-4 py-2 font-bold text-orange-700">{{ number_format($Ui[$idx], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>

</body>

</html>
