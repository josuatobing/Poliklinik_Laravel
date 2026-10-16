<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPeriksa::where(
            'id_dokter',
            Auth::id()
        )->orderByRaw("
            FIELD(hari, 'Senin', 'Selasa', 'Rabu',
            'Kamis', 'Jumat', 'Sabtu', 'Minggu')
        ")->get();

        return view('dokter.jadwal-periksa.index', compact('jadwals'));
    }

    public function create()
    {
        return view('dokter.jadwal-periksa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        JadwalPeriksa::create([
            'id_dokter' => Auth::id(),
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()
            ->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil ditambahkan')
            ->with('type', 'success');
    }

    public function edit(JadwalPeriksa $jadwal_periksa)
    {
        abort_unless(
            $jadwal_periksa->id_dokter === Auth::id(),
            403
        );

        return view(
            'dokter.jadwal-periksa.edit',
            compact('jadwal_periksa')
        );
    }

    public function update(
        Request $request,
        JadwalPeriksa $jadwal_periksa
    ) {
        abort_unless(
            $jadwal_periksa->id_dokter === Auth::id(),
            403
        );

        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $jadwal_periksa->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()
            ->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil diubah')
            ->with('type', 'success');
    }

    public function destroy(JadwalPeriksa $jadwal_periksa)
    {
        abort_unless(
            $jadwal_periksa->id_dokter === Auth::id(),
            403
        );

        try {
            $jadwal_periksa->delete();

            return redirect()
                ->route('jadwal-periksa.index')
                ->with('message', 'Jadwal periksa berhasil dihapus')
                ->with('type', 'success');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()
                ->route('jadwal-periksa.index')
                ->with('message', 'Jadwal tidak dapat dihapus karena masih digunakan')
                ->with('type', 'error');
        }
    }
}
