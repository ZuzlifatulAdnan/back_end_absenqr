<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Admin',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'guru1',
                'email' => 'guru1@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Guru',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'guru2',
                'email' => 'guru2@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Guru',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'siswa1',
                'email' => 'siswa1@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Siswa',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'siswa2',
                'email' => 'siswa2@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Siswa',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
        DB::table('gurus')->insert([
            [
                'user_id' => '2',
                'nip' => '111222',
                'jenis_kelamin' => 'Laki-laki',
                'no_telepon' => '6282912190',
            ],
            [
                'user_id' => '3',
                'nip' => '222333',
                'jenis_kelamin' => 'Perempuan',
                'no_telepon' => '6282778721',
            ],
        ]);
        DB::table('kelas')->insert([
            [
                'nama' => 'XII IPA',
                'status' => 'Aktif',
                'latitude' => '-5.384637',
                'longitude' => '105.260364',
                'radius' => '25',
            ],
        ]);
        DB::table('mapels')->insert([
            [
                'nama' => 'Fisika',
                'kode' => 'FISIK01',
                'guru_id' => '1',
            ],
            [
                'nama' => 'Kimia',
                'kode' => 'KIM01',
                'guru_id' => '2',
            ],
        ]);
        DB::table('jadwals')->insert([
            [
                'guru_id' => '1',
                'kelas_id' => '1',
                'mapel_id' => '1',
                'hari' => 'Senin',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '09:00:00',
            ],
            [
                'guru_id' => '2',
                'kelas_id' => '1',
                'mapel_id' => '2',
                'hari' => 'Senin',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '10:00:00',
            ]
        ]);
        DB::table('siswas')->insert([
            [
                'user_id' => '4',
                'kelas_id' => '1',
                'jenis_kelamin' => 'Perempuan',
            ],
             [
                'user_id' => '5',
                'kelas_id' => '1',
                'jenis_kelamin' => 'Laki-laki',
            ],
        ]);
    }
}
