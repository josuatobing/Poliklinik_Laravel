<x-layouts.app title="Periksa Pasien">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Periksa Pasien</h2>
        <p class="text-sm text-slate-500 mt-1">
            Daftar pasien yang terdaftar pada jadwal praktik Anda.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-xl bg-green-100 text-green-700 p-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('message'))
        <div class="mb-5 rounded-xl bg-green-100 text-green-700 p-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600">
                            <th>ID</th>
                            <th>Nama Pasien</th>
                            <th>Keluhan</th>
                            <th>Nomor Antrean</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($daftarPasien as $daftar)
                            <tr>
                                <td>{{ $daftar->id }}</td>

                                <td class="font-medium text-slate-800">
                                    {{ $daftar->pasien->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $daftar->keluhan ?: '-' }}
                                </td>

                                <td>
                                    <span class="inline-flex items-center justify-center rounded-lg bg-blue-100 text-blue-700 font-bold px-3 py-1">
                                        {{ $daftar->no_antrian }}
                                    </span>
                                </td>

                                <td>
                                    @if ($daftar->periksas->isNotEmpty())
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Sudah Diperiksa
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            Belum Diperiksa
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($daftar->periksas->isEmpty())
                                        <a href="{{ route('periksa-pasien.create', $daftar->id) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white hover:opacity-90 transition">
                                            <i class="fas fa-stethoscope"></i>
                                            Periksa
                                        </a>
                                    @else
                                        <span class="text-sm text-slate-400">
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-slate-500">
                                    Belum ada pasien dalam daftar antrean.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>