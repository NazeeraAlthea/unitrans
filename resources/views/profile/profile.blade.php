<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="fixed top-10 px-10 text-xl font-bold text-gray-900 flex justify-between w-full">
        <a href="{{ route('home') }}">Unitrans</a>
        <a href="{{ route('logout-mahasiswa') }}"
            class="px-6 py-2 rounded-lg border-2 border-blue-600 text-blue-600 font-semibold hover:bg-blue-600 hover:text-white transition">
            Logout
        </a>
    </div>

    <div class="max-w-2xl w-full mx-auto my-12 p-6 bg-white rounded-2xl shadow-lg">
        <!-- Profil -->
        <div class="flex flex-col items-center">
            <img src="/img/user-default.png" alt="Foto Profil"
                class="w-28 h-28 rounded-full shadow border mb-3 object-cover">
            <div class="text-2xl font-bold text-[#007BFF] mb-1">{{ $mahasiswa->nama }}</div>
            <div class="text-base text-gray-600 mb-6">{{ $mahasiswa->email }}</div>
        </div>
        <hr class="my-8">

        <!-- Riwayat Perhitungan -->
        <div>
            <h2 class="text-xl font-semibold mb-4 text-[#BA68C8]">Riwayat Rekomendasi</h2>
            <div class="flex flex-col gap-4">
                @forelse($riwayat as $item)
                    <div
                        class="flex items-center gap-4 p-4 rounded-xl shadow hover:bg-[#E3F2FD] transition cursor-pointer border border-[#E3F2FD] group">
                        <a href="{{ url('/hasil-rekomendasi/' . $item->id_perhitungan) }}" class="flex-1 block">
                            <div class="font-semibold text-[#007BFF]">
                                {{ $item->juara_1 ? "Rekomendasi: $item->juara_1" : "Rekomendasi #$item->id_perhitungan" }}
                            </div>
                            <div class="text-sm text-gray-500">
                                @if (isset($item->waktu_perhitungan))
                                    {{ \Carbon\Carbon::parse($item->waktu_perhitungan)->format('d M Y, H:i') }}
                                @endif
                            </div>
                        </a>
                        <form method="POST" action="{{ route('delete-history', $item->id_perhitungan) }}"
                            onsubmit="return confirm('Yakin ingin menghapus riwayat ini?')" class="ml-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1 rounded-lg bg-red-500 text-white hover:bg-red-700 transition font-semibold text-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-gray-400 italic">Belum ada riwayat rekomendasi.</div>
                @endforelse

            </div>
        </div>
    </div>

</body>

</html>
