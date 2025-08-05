<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'izin';

        $query = Izin::with('siswa.user', 'siswa.kelas');

        // Filter berdasarkan role
        if (Auth::user()->role === 'Siswa') {
            $siswaId = Auth::user()->siswa->id ?? null;
            $query->where('siswa_id', $siswaId);
        }

        // Filter kelas (untuk admin/guru)
        if (Auth::user()->role !== 'Siswa') {
            $query->when($request->kelas_id, function ($q) use ($request) {
                $q->whereHas('siswa.kelas', function ($k) use ($request) {
                    $k->where('id', $request->kelas_id);
                });
            });
        }

        // Filter tanggal
        $query->when($request->tanggal, function ($q) use ($request) {
            $q->whereDate('tanggal', $request->tanggal);
        });

        // Pencarian nama siswa
        $query->when($request->search, function ($q) use ($request) {
            $q->whereHas('siswa.user', function ($q2) use ($request) {
                $q2->where('name', 'like', '%' . $request->search . '%');
            });
        });

        $izin = $query->latest()->paginate(10);

        // Ambil data kelas hanya untuk admin/guru
        $kelas = Auth::user()->role !== 'Siswa' ? Kelas::all() : [];

        return view('pages.izin.index', compact('izin', 'type_menu', 'kelas'));
    }
    public function create()
    {
        $type_menu = 'izin';
        $siswa = Siswa::with('user')->get();
        $kelas = Kelas::all();

        return view('pages.izin.create', compact('type_menu', 'siswa', 'kelas'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'Siswa') {
            $request->merge([
                'siswa_id' => Auth::user()->siswa->id,
                'kelas_id' => Auth::user()->siswa->kelas_id,
            ]);
        }

        $validatedData = $request->validate([
            'siswa_id' => 'required',
            'kelas_id' => 'required',
            'tanggal_izin' => 'required|date',
            'alasan' => 'required|string',
            'bukti_surat' => 'required|mimes:jpg,jpeg,png,gif',
        ]);

        $imagePath = null;
        if ($request->hasFile('bukti_surat')) {
            $image = $request->file('bukti_surat');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/izin/', $imagePath);
        }

        $izin = Izin::create([
            'siswa_id' => $validatedData['siswa_id'],
            'kelas_id' => $validatedData['kelas_id'],
            'tanggal_izin' => $validatedData['tanggal_izin'],
            'alasan' => $validatedData['alasan'],
            'bukti_surat' => $imagePath,
        ]);

        return redirect()->route('izin.index')->with('success', 'Izin ' . $izin->siswa->user->name . ' berhasil ditambah.');
    }


    public function edit(Izin $izin)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        // Cek role
        if ($user->role == 'Admin' || ($user->role =='Siswa' && $izin->siswa_id == $siswa->id)) {
            $type_menu = 'izin';
            $siswas = Siswa::with('user')->get();
            $kelas = Kelas::all();
            return view('pages.izin.edit', compact('type_menu', 'izin', 'siswas', 'kelas'));
        }

        abort(403);
    }

    public function update(Request $request, Izin $izin)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!($user->role == 'Admin' || ($user->role =='siswa' && $izin->siswa_id == $siswa->id))) {
            abort(403);
        }

        $request->validate([
            'tanggal_izin' => 'required|date',
            'alasan' => 'required|string|max:255',
        ]);

        $izin->update([
            'tanggal_izin' => $request->tanggal_izin,
            'alasan' => $request->alasan,
        ]);

        return redirect()->route('izin.index')->with('success', 'Data izin berhasil diperbarui.');
    }

    public function show(Izin $izin)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if ($user->role =='Siswa' && $izin->siswa_id != $siswa->id) {
            abort(403);
        }

        $type_menu = 'izin';
        return view('pages.izin.show', compact('type_menu', 'izin'));
    }
    public function destroy(Izin $izin)
    {
        $izin->delete();
        return Redirect::route('izin.index')->with('success', 'Izin dengan Nama' . $izin->siswa->user->name . 'berhasil di hapus.');
    }
}
