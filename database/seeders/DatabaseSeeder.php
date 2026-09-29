<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Instansi;
use App\Models\Application;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role_id' => '1',
        ]);

        User::create([
            'name' => 'Bung Steno',
            'gender' => 'Laki-Laki',
            'position' => 'Mahasiswa',
            'prodi' => 'Teknik Informatika',
            'email' => 'user@gmail.com',
            'password' => bcrypt('user123'),
            'role_id' => '2',
        ]);

        Instansi::create([
            'name' => 'Universitas Dhyana Pura',
            'adress' => 'Jl. Padang Luwih',
            'radius' => '20',
            'leader_name' => 'Rai Utama',
            'latitude' => '-7.323212083708643',
            'longitude' => '108.3457463090454'
        ]);

        Role::create([
            'name' => 'Admin',
        ]);

        Role::create([
            'name' => 'User',
        ]);

        Application::create([
            'app_name' => 'E-Presensi',
            'copyright' => 'Bung_Steno Developer',
            'color' => '#016773',
            'is_active' => '1',
            'is_photo' => '1',
        ]);
    }
}
