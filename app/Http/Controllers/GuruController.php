<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'sekolah';

        $keyword = trim($request->input('nama'));

        // cari nama dari relasi ke user
        $guru = Guru::with('user')
            ->when($keyword, function ($query, $name) {
                $query->whereHas('user', function ($q) use ($name) {
                    $q->where('name', 'like', '%' . $name . '%');
                });
            })
            ->latest()
            ->paginate(10);

        $guru->appends(['nama' => $keyword]);

        return view('pages.guru.index', compact('type_menu', 'guru'));
    }
    public function create()
    {
        $type_menu = 'sekolah';
        $users = User::where('role', 'Guru')->get();

        return view('pages.guru.create', compact('type_menu', 'users'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'user_id' => 'required',
            'nip' => 'required',
            'jenis_kelamin' => 'required',
            'no_telepon' => [
                'required',
                'regex:/^628/',
            ],
        ]);

        $guru = Guru::create([
            'user_id' => $request->user_id,
            'nip' => $request->nip,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telepon' => $request->no_telepon,
        ]);

        return Redirect::route('guru.index')->with('success', 'Guru ' . $guru->user->name . ' berhasil di tambah.');
    }

    public function edit(Guru $guru)
    {
        $type_menu = 'sekolah';
        $users = User::where('role', 'Guru')->get();

        return view('pages.guru.edit', compact('guru', 'type_menu', 'users'));

    }
    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'user_id' => 'required',
            'nip' => 'required',
            'jenis_kelamin' => 'required',
            'no_telepon' => [
                'required',
                'regex:/^628/',
            ],
        ]);

        $guru->update([
            'user_id' => $request->user_id,
            'nip' => $request->nip,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telepon' => $request->no_telepon,
        ]);

        return Redirect::route('guru.index')->with('success', 'Guru ' . $guru->user->name . ' berhasil di ubah.');
    }
    public function destroy(Guru $guru)
    {
        $guru->delete();
        return Redirect::route('guru.index')->with('success', 'Guru ' . $guru->user->name . ' berhasil di hapus.');
    }
}
