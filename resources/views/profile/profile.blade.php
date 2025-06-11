<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="fixed top-10 px-10 text-xl font-bold text-gray-900 flex justify-between w-full">
        <a href="{{ route('home') }}">Unitrans</a>
        <a href="{{ route('logout-mahasiswa') }}"
            class="px-6 py-2 rounded-lg border-2 border-red-600 text-red-600 font-semibold hover:bg-red-600 hover:text-white transition">
            Logout
        </a>
    </div>

    <div class="max-w-2xl w-full mx-auto my-12 p-6 bg-white rounded-2xl shadow-lg">
        <!-- Profil -->
        <div class="flex flex-col items-center">
            <img src="/images/user-default.png" alt="Foto Profil"
                class="w-28 h-28 rounded-full shadow border border-gray-800 mb-3 object-cover">
            <div class="flex items-center gap-2 justify-center" x-data="{ edit: false, nama: '{{ $mahasiswa->nama }}', oldNama: '{{ $mahasiswa->nama }}' }">
                <!-- Tampilan Nama + Tombol Edit -->
                <template x-if="!edit">
                    <div class="flex items-center gap-2 group">
                        <span class="text-2xl font-bold text-[#007BFF] transition-all" x-text="nama"></span>
                        <button type="button" @click="edit = true"
                            class="ml-1 p-1 rounded-full hover:bg-blue-50 hover:text-blue-700 transition"
                            title="Edit Nama">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    d="M16.862 5.487a1.63 1.63 0 0 1 2.305 2.305l-9.43 9.429a1.63 1.63 0 0 1-.66.406l-3.21.96a.326.326 0 0 1-.4-.4l.96-3.21a1.63 1.63 0 0 1 .406-.66l9.43-9.43Z" />
                            </svg>
                        </button>
                    </div>
                </template>
                <!-- Tampilan Edit Inline -->
                <template x-if="edit">
                    <form action="{{ route('update-nama-mahasiswa') }}" method="POST" class="flex items-center gap-2"
                        @submit="setTimeout(() => { edit = false }, 300)">
                        @csrf
                        <input type="text" name="nama" x-model="nama"
                            class="border border-blue-300 rounded-full px-4 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all shadow-sm"
                            required autocomplete="off" style="width: 180px" x-ref="namaInput"
                            @keydown.enter="$el.form.submit()" @keydown.escape="nama = oldNama; edit = false"
                            placeholder="Masukkan nama baru" x-init="$nextTick(() => $refs.namaInput.focus())">
                        <button type="submit"
                            class="bg-blue-600 text-white px-5 py-2 rounded-full shadow hover:bg-blue-700 font-semibold transition-all">
                            Simpan
                        </button>
                        <button type="button" @click="nama = oldNama; edit = false"
                            class="ml-1 p-2 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition text-xl"
                            title="Batal">
                            &times;
                        </button>
                    </form>
                </template>
            </div>

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
