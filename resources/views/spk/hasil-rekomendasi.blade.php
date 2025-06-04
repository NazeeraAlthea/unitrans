<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Rekomendasi Teratas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen font-sans">
    <div class="max-w-2xl mx-auto py-12 px-4 bg-white rounded-3xl shadow-lg">
        <h1 class="text-3xl font-bold text-center mb-10">HASIL REKOMENDASI TERATAS</h1>

        @php
            $juara1 = $ranking[0] ?? null;
            $juara2 = $ranking[1] ?? null;
            $juara3 = $ranking[2] ?? null;
        @endphp

        <!-- Juara 1 -->
        @if ($juara1)
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-4">
                <!-- Trophy -->
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 flex items-center justify-center">
                        <span class="text-yellow-400 text-6xl">🏆</span>
                    </div>
                </div>
                <!-- Placeholder Icon/Gambar -->
                <div
                    class="w-60 h-40 rounded-2xl bg-gray-200 flex items-center justify-center border shadow mb-4 sm:mb-0 text-5xl">
                    {{ $juara1['icon'] ?? '🚌' }}
                </div>

                @php
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
                <!-- Detail -->
                <div class="sm:ml-6 flex-1">
                    <div class="text-base mb-1">Harga : <span class="font-semibold">Rp
                            {{ number_format($juara1['detail']['harga'] ?? 0, 0, ',', '.') }}</span></div>
                    <div class="text-base mb-1">Waktu : <span
                            class="font-semibold">{{ waktuFormat($juara1['detail']['waktu'] ?? '-') }} Menit</span>
                    </div>
                    <div class="text-base mb-1">Keamanan : <span
                            class="font-semibold">{{ $juara1['detail']['keamanan'] ?? '-' }}</span></div>
                    <div class="text-base mb-1">Kenyamanan : <span
                            class="font-semibold">{{ $juara1['detail']['kenyamanan'] ?? '-' }}</span></div>
                    <div class="text-base mb-1">Aksesbilitas : <span
                            class="font-semibold">{{ $juara1['detail']['aksesbilitas'] ?? '-' }}</span></div>
                </div>
            </div>


            <div class="text-center font-bold text-xl">{{ $juara1['nama'] }}</div>
            <div class="text-center text-gray-600 text-lg mb-8">{{ number_format($juara1['skor'], 2) }}/100</div>
        @endif

        <!-- Juara 2 & 3 -->
        <div class="flex flex-row items-center justify-center gap-8 mb-10">
            @if ($juara2)
                <div class="flex flex-col items-center">
                    <div class="relative">
                        <div class="absolute -top-5 -left-5 text-3xl text-gray-400">2</div>
                        <div
                            class="w-28 h-20 bg-gray-200 flex items-center justify-center rounded-xl border shadow text-3xl">
                            {{ $juara2['icon'] ?? '🚌' }}
                        </div>
                    </div>
                    <div class="font-semibold mt-2">{{ $juara2['nama'] }}</div>
                    <div class="text-gray-500">{{ number_format($juara2['skor'], 2) }}/100</div>
                </div>
            @endif
            @if ($juara3)
                <div class="flex flex-col items-center">
                    <div class="relative">
                        <div class="absolute -top-5 -left-5 text-3xl text-orange-400">3</div>
                        <div
                            class="w-28 h-20 bg-gray-200 flex items-center justify-center rounded-xl border shadow text-3xl">
                            {{ $juara3['icon'] ?? '🚗' }}
                        </div>
                    </div>
                    <div class="font-semibold mt-2">{{ $juara3['nama'] }}</div>
                    <div class="text-gray-500">{{ number_format($juara3['skor'], 2) }}/100</div>
                </div>
            @endif
        </div>

        <!-- Garis Pembatas -->
        <div class="border-t-2 border-gray-200 my-6"></div>

        <!-- Bobot Kriteria -->
        <div class="text-center text-lg font-semibold mb-4">Bobot yang diterapkan</div>
        <div class="flex flex-wrap justify-center gap-4">
            @foreach ($bobot as $bk)
                <div class="flex items-center bg-gray-50 px-4 py-2 rounded-xl shadow text-base font-semibold">
                    @switch($bk['nama_kriteria'])
                        @case('harga')
                            <span class="mr-2">💸</span>
                        @break

                        @case('waktu')
                            <span class="mr-2">⏰</span>
                        @break

                        @case('keamanan')
                            <span class="mr-2">🛡️</span>
                        @break

                        @case('kenyamanan')
                            <span class="mr-2">🛋️</span>
                        @break

                        @case('aksesbilitas')
                            <span class="mr-2">♿</span>
                        @break

                        @default
                            <span class="mr-2">🔹</span>
                    @endswitch
                    {{ ucfirst($bk['nama_kriteria']) }}
                    <span class="ml-2 font-bold text-blue-500">
                        {{ round($bk['bobot'] * 100) }}%
                    </span>
                </div>
            @endforeach
        </div>
        <!-- Tambahan Table Penilaian Lengkap -->

        <div class="max-w-3xl mx-auto my-10 bg-white rounded-2xl shadow p-8">
            <h2 class="text-2xl font-bold mb-4 text-center">Tabel Matriks Alternatif</h2>
            <div class="overflow-x-auto">
                <table class="table-auto border-collapse w-full text-center">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-3 py-1">Transportasi</th>
                            @foreach ($kriteriaArr as $kr)
                                <th class="border px-3 py-1">{{ ucfirst($kr) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nilaiAlternatif as $row)
                            <tr>
                                <td class="border px-3 py-1 font-semibold">{{ $row['nama'] }}</td>
                                @foreach ($kriteriaArr as $kr)
                                    <td class="border px-3 py-1">{{ $row[$kr] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="max-w-3xl mx-auto my-10 bg-white rounded-2xl shadow p-8">
            <h2 class="text-2xl font-bold mb-4 text-center">Tabel Normalisasi</h2>
            <div class="overflow-x-auto">
                <table class="table-auto border-collapse w-full text-center">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-3 py-1">Alternatif</th>
                            @foreach ($kriteriaArr as $kr)
                                <th class="border px-3 py-1">{{ ucfirst($kr) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transportasi as $idx => $alt)
                            <tr>
                                <td class="border px-3 py-1 font-semibold">{{ $alt['nama'] }}</td>
                                @foreach ($normalisasi[$idx] as $v)
                                    <td class="border px-3 py-1">{{ number_format($v, 4) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="max-w-3xl mx-auto my-10 bg-white rounded-2xl shadow p-8">
            <h2 class="text-2xl font-bold mb-4 text-center">Tabel Matriks Berbobot</h2>
            <div class="overflow-x-auto">
                <table class="table-auto border-collapse w-full text-center">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-3 py-1">Alternatif</th>
                            @foreach ($kriteriaArr as $kr)
                                <th class="border px-3 py-1">{{ ucfirst($kr) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transportasi as $idx => $alt)
                            <tr>
                                <td class="border px-3 py-1 font-semibold">{{ $alt['nama'] }}</td>
                                @foreach ($berbobot[$idx] as $v)
                                    <td class="border px-3 py-1">{{ number_format($v, 4) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="max-w-3xl mx-auto my-10 bg-white rounded-2xl shadow p-8">
            <h2 class="text-2xl font-bold mb-4 text-center">Tabel Perhitungan Akhir</h2>
            <div class="overflow-x-auto">
                <table class="table-auto border-collapse w-full text-center">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-3 py-1">Alternatif</th>
                            <th class="border px-3 py-1">S+</th>
                            <th class="border px-3 py-1">S-</th>
                            <th class="border px-3 py-1">Qi</th>
                            <th class="border px-3 py-1">Ui</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transportasi as $idx => $alt)
                            <tr>
                                <td class="border px-3 py-1 font-semibold">{{ $alt['nama'] }}</td>
                                <td class="border px-3 py-1">{{ number_format($Splus[$idx], 4) }}</td>
                                <td class="border px-3 py-1">{{ number_format($Smin[$idx], 4) }}</td>
                                <td class="border px-3 py-1">{{ number_format($Qi[$idx], 4) }}</td>
                                <td class="border px-3 py-1">{{ number_format($Ui[$idx], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>
