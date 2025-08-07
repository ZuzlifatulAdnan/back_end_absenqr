<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $type_menu = 'profile';
        $user = Auth::user();
        return view('pages.profile.index', compact('type_menu', 'user', ));
    }
    public function edit()
    {
        $type_menu = 'profile';
        $kelasList = Kelas::all(); // jika role siswa
        return view('pages.profile.edit', compact('type_menu', 'kelasList'));
    }

    public function update(Request $request, User $user)
    {
        $role = $user->role;

        // Validasi umum
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($role === 'Siswa') {
            $rules = array_merge($rules, [
                // siswa
                'nis' => 'required|unique:siswas,nis,' . ($user->siswa->id ?? 'NULL'),
                'nisn' => 'required|unique:siswas,nisn,' . ($user->siswa->id ?? 'NULL'),
                'kelas_id' => 'required|exists:kelas,id',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'no_telepon' => 'required|regex:/^628/',
                // orangtua
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

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update siswa
        if ($role === 'Siswa' && $user->siswa) {
            $user->siswa->update([
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'],
                'kelas_id' => $validated['kelas_id'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon' => $validated['no_telepon'],
            ]);

            // Update atau buat data orangtua
            if ($user->siswa->ortu) {
                $user->siswa->ortu->update([
                    'nama' => $validated['nama'],
                    'alamat' => $validated['alamat'],
                    'pekerjaan' => $validated['pekerjaan'],
                    'no_telepon' => $validated['no_telepon_ortu'],
                    'hubungan' => $validated['hubungan'],
                ]);
            } else {
                // Buat data orangtua jika belum ada
                $user->siswa->ortu()->create([
                    'nama' => $validated['nama'],
                    'alamat' => $validated['alamat'],
                    'pekerjaan' => $validated['pekerjaan'],
                    'no_telepon' => $validated['no_telepon_ortu'],
                    'hubungan' => $validated['hubungan'],
                ]);
            }
        }

        // Update guru
        if ($role === 'Guru' && $user->guru) {
            $user->guru->update([
                'nip' => $validated['nip'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon' => $validated['no_telepon'],
            ]);
        }

        // Update foto jika ada
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('img/user'), $filename);

            // Hapus gambar lama
            if ($user->image && file_exists(public_path('img/user/' . $user->image))) {
                unlink(public_path('img/user/' . $user->image));
            }

            $user->update([
                'image' => $filename
            ]);
        }

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePasswordForm()
    {
        $type_menu = 'profile';
        return view('pages.profile.change-password', compact('type_menu'));
    }
    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update the new password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.index')->with('success', 'password Akun ' . $user->name . ' berhasil diperbarui.');
    }
}
