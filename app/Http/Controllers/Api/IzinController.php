<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
   public function index(Request $request)
    {
        $user = Auth::user();
        $query = Izin::with('siswa.user', 'siswa.kelas');

        if ($user->role === 'Siswa') {
            $query->where('siswa_id', $user->siswa->id ?? null);
        } else {
            if ($request->kelas_id) {
                $query->whereHas('siswa.kelas', function ($k) use ($request) {
                    $k->where('id', $request->kelas_id);
                });
            }
        }

        if ($request->tanggal) {
            $query->whereDate('tanggal_izin', $request->tanggal);
        }

        if ($request->search) {
            $query->whereHas('siswa.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $izin = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Data izin berhasil diambil.',
            'data' => $izin
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'Siswa') {
            $request->merge([
                'siswa_id' => $user->siswa->id,
                'kelas_id' => $user->siswa->kelas_id,
            ]);
        }

        $validatedData = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_izin' => 'required|date',
            'alasan' => 'required|string',
            'bukti_surat' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('bukti_surat')) {
            $image = $request->file('bukti_surat');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/izin/'), $imagePath);
        }

        $izin = Izin::create([
            'siswa_id' => $validatedData['siswa_id'],
            'kelas_id' => $validatedData['kelas_id'],
            'tanggal_izin' => $validatedData['tanggal_izin'],
            'alasan' => $validatedData['alasan'],
            'bukti_surat' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data izin berhasil ditambahkan.',
            'data' => $izin->load('siswa.user', 'siswa.kelas')
        ]);
    }

    public function show($id)
    {
        $izin = Izin::with('siswa.user', 'siswa.kelas')->findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'Siswa' && $izin->siswa_id !== $user->siswa->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data ini.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail izin berhasil diambil.',
            'data' => $izin
        ]);
    }
}
