<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\Siswa_orangTua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'siswa';

        $keyword = trim($request->input('search'));
        $kelasId = $request->input('kelas'); // kelas yang dipilih

        $query = Siswa::with(['user', 'kelas']);

        if ($keyword) {
            $query->whereHas('user', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            });
        }

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $siswas = $query->latest()->paginate(10);
        $siswas->appends($request->only(['search', 'kelas']));

        $listKelas = Kelas::all();

        return view('pages.siswa.index', [
            'type_menu' => $type_menu,
            'siswas' => $siswas,
            'listKelas' => $listKelas,
            'kelasId' => $kelasId,
            'keyword' => $keyword
        ]);
    }
    public function create()
    {
        $type_menu = 'siswa';
        $listKelas = Kelas::all();
        $listUser = User::where('role', 'Siswa')->get();

        return view('pages.siswa.create', [
            'type_menu' => $type_menu,
            'listKelas' => $listKelas,
            'listUser' => $listUser
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|unique:siswas,user_id',
            'kelas_id' => 'required',
            'nis' => 'required|unique:siswas,nis',
            'nisn' => 'required|unique:siswas,nisn',
            'jenis_kelamin' => 'required',
            'no_telepon' => [
                'required',
                'regex:/^628/',
            ],
            'nama' => 'required',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'no_telepon_ortu' => [
                'required',
                'regex:/^628/',
            ],
            'hubungan_ortu' => 'required',
        ]);

        try {
            $siswa = Siswa::create([
                'user_id' => $request->user_id,
                'kelas_id' => $request->kelas_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_telepon' => $request->no_telepon,
            ]);

            OrangTua::create([
                'siswa_id' => $siswa->id,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'no_telepon' => $request->no_telepon_ortu,
                'hubungan' => $request->hubungan_ortu,
            ]);

            return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil disimpan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }
    public function edit(Siswa $siswa)
    {
        $type_menu = 'siswa';
        $listKelas = Kelas::where('status', 'Aktif')->get();
        $listUser = User::where('role', 'Siswa')->get();

        // Ambil data orang tua berdasarkan siswa_id
        $ortu = OrangTua::where('siswa_id', $siswa->id)->first();
        $hubunganOptions = ['Ayah', 'Ibu', 'Wali'];

        return view('pages.siswa.edit', compact(
            'type_menu',
            'listKelas',
            'listUser',
            'siswa',
            'ortu',
            'hubunganOptions'
        ));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'user_id' => ['required', Rule::unique('siswas')->ignore($siswa->id)],
            'kelas_id' => 'required',
            'nis' => ['required', Rule::unique('siswas')->ignore($siswa->id)],
            'nisn' => ['required', Rule::unique('siswas')->ignore($siswa->id)],
            'jenis_kelamin' => 'required',
            'no_telepon' => [
                'required',
                'regex:/^628/',
            ],

            'nama' => 'required',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'no_telepon_ortu' => [
                'required',
                'regex:/^628/',
            ],
            'hubungan_ortu' => 'required',
        ]);

        try {
            // Update data siswa
            $siswa->update([
                'user_id' => $request->user_id,
                'kelas_id' => $request->kelas_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_telepon' => $request->no_telepon,
            ]);

            // Update atau buat data orang tua
            OrangTua::updateOrCreate(
                ['siswa_id' => $siswa->id],
                [
                    'nama' => $request->nama,
                    'alamat' => $request->alamat,
                    'pekerjaan' => $request->pekerjaan,
                    'no_telepon' => $request->no_telepon_ortu,
                    'hubungan' => $request->hubungan_ortu,
                ]
            );

            return redirect()->route('siswa.index')->with('success', 'Data ' . $siswa->user->name . ' berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Siswa $siswa)
    {
        $type_menu = 'siswa';
        $ortu = OrangTua::where('siswa_id', $siswa->id)->first();

        return view('pages.siswa.show', compact(
            'type_menu',
            'siswa',
            'ortu'
        ));
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return Redirect::route('siswa.index')->with('success', $siswa->user->name . 'berhasil di hapus.');
    }
}
