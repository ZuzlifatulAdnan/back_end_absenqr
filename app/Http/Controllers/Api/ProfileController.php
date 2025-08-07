<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'message' => 'Profil user berhasil diambil.',
            'data' => $user->load('siswa.kelas', 'siswa.ortu', 'guru'),
        ]);
    }

    public function edit()
    {
        $kelasList = Kelas::all();
        return response()->json([
            'success' => true,
            'kelas' => $kelasList
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($role === 'Siswa') {
            $rules = array_merge($rules, [
                'nis' => 'required|unique:siswas,nis,' . ($user->siswa->id ?? 'NULL'),
                'nisn' => 'required|unique:siswas,nisn,' . ($user->siswa->id ?? 'NULL'),
                'kelas_id' => 'required|exists:kelas,id',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'no_telepon' => 'required|regex:/^628/',
                'nama' => 'required',
                'alamat' => 'required',
                'pekerjaan' => 'required',
                'no_telepon_ortu' => 'required|regex:/^628/',
                'hubungan' => 'required',
            ]);
        } elseif ($role === 'Guru') {
            $rules = array_merge($rules, [
                'nip' => 'required|unique:gurus,nip,' . ($user->guru->id ?? 'NULL'),
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'no_telepon' => 'required|regex:/^628/',
            ]);
        }

        $validated = $request->validate($rules);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($role === 'Siswa' && $user->siswa) {
            $user->siswa->update([
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'],
                'kelas_id' => $validated['kelas_id'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon' => $validated['no_telepon'],
            ]);

            $ortu = $user->siswa->ortu;
            $ortuData = [
                'nama' => $validated['nama'],
                'alamat' => $validated['alamat'],
                'pekerjaan' => $validated['pekerjaan'],
                'no_telepon' => $validated['no_telepon_ortu'],
                'hubungan' => $validated['hubungan'],
            ];

            if ($ortu) {
                $ortu->update($ortuData);
            } else {
                $user->siswa->ortu()->create($ortuData);
            }
        }

        if ($role === 'Guru' && $user->guru) {
            $user->guru->update([
                'nip' => $validated['nip'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon' => $validated['no_telepon'],
            ]);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('img/user'), $filename);

            if ($user->image && file_exists(public_path('img/user/' . $user->image))) {
                unlink(public_path('img/user/' . $user->image));
            }

            $user->update(['image' => $filename]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $user->fresh()->load('siswa.kelas', 'siswa.ortu', 'guru')
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama salah.'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.'
        ]);
    }
}
