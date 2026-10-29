<x-layouts.app title="Pendaftaran Periksa">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pasien.dashboard') }}"
            class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Pendaftaran Periksa
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Silakan lengkapi formulir pendaftaran pemeriksaan.
            </p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('message'))
        <div class="mb-5 rounded-xl p-4
            {{ session('type') === 'success'
                ? 'bg-green-100 text-green-700'
                : 'bg-red-100 text-red-700' }}">
            {{ session('message') }}
        </div>
    @endif

    {{-- Error Validasi --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl bg-red-100 text-red-700 p-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Pendaftaran --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-6 md:p-8">

            <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                @csrf

                {{-- Nomor Rekam Medis --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nomor Rekam Medis
                    </label>

                    <input type="text"
                        value="{{ $user->no_rm ?? 'Belum tersedia' }}"
                        class="w-full px-4 py-3 border rounded-lg bg-slate-100 text-slate-600 cursor-not-allowed"
                        readonly>

                    <p class="text-xs text-slate-500 mt-1">
                        Nomor rekam medis terisi otomatis dan tidak dapat diubah.
                    </p>
                </div>

                {{-- Pilih Poli --}}
                <div class="mb-6">
                    <label for="id_poli"
                        class="block text-sm font-semibold text-slate-700 mb-2">
                        Pilih Poli <span class="text-red-500">*</span>
                    </label>

                    <select name="id_poli" id="id_poli"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-primary"
                        required>
                        <option value="">-- Pilih Poli --</option>

                        @foreach ($polis as $poli)
                            <option value="{{ $poli->id }}"
                                {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->nama_poli }}
                            </option>
                        @endforeach
                    </select>

                    @error('id_poli')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pilih Jadwal --}}
                <div class="mb-6">
                    <label for="id_jadwal"
                        class="block text-sm font-semibold text-slate-700 mb-2">
                        Pilih Jadwal Dokter <span class="text-red-500">*</span>
                    </label>

                    <select name="id_jadwal" id="id_jadwal"
    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-primary"
    required>
    <option value="">-- Pilih Jadwal Dokter --</option>

    @foreach ($jadwals as $jadwal)
        @if ($jadwal->dokter)
            <option
                value="{{ $jadwal->id }}"
                data-poli="{{ $jadwal->dokter->id_poli }}"
                {{ old('id_jadwal') == $jadwal->id ? 'selected' : '' }}>
                {{ $jadwal->dokter->nama }}
                — {{ $jadwal->hari }}
                ({{ substr($jadwal->jam_mulai, 0, 5) }} -
                {{ substr($jadwal->jam_selesai, 0, 5) }})
            </option>
        @endif
    @endforeach
</select>

                    @error('id_jadwal')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keluhan --}}
                <div class="mb-8">
                    <label for="keluhan"
                        class="block text-sm font-semibold text-slate-700 mb-2">
                        Keluhan
                    </label>

                    <textarea name="keluhan" id="keluhan" rows="4"
                        placeholder="Tuliskan keluhan yang dirasakan..."
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-primary">{{ old('keluhan') }}</textarea>

                    @error('keluhan')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex flex-wrap gap-3">
                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-primary hover:bg-primary/90 text-white font-semibold transition">
                        <i class="fas fa-calendar-check mr-1"></i>
                        Daftar Pemeriksaan
                    </button>

                    <a href="{{ route('pasien.dashboard') }}"
                        class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold transition">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Filter jadwal berdasarkan poli --}}
    <script>
    const poliSelect = document.getElementById('id_poli');
    const jadwalSelect = document.getElementById('id_jadwal');

    function filterJadwal() {
        const poliId = poliSelect.value;
        const selectedValue = jadwalSelect.value;

        Array.from(jadwalSelect.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = !poliId ||
                option.dataset.poli !== poliId;
        });

        if (
            jadwalSelect.selectedOptions.length &&
            jadwalSelect.selectedOptions[0].hidden
        ) {
            jadwalSelect.value = '';
        }
    }

    poliSelect.addEventListener('change', filterJadwal);

    filterJadwal();
</script>

</x-layouts.app>