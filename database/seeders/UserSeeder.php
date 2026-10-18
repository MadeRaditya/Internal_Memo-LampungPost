<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itDept = Department::where('code', 'IT')->first();
        $umumDept = Department::where('code', 'UMUM')->first();
        $keuDept = Department::where('code', 'KEU')->first();

        // 1. Super Admin
        User::updateOrCreate(['email' => 'admin@lampost.co.id'], [
            'department_id' => $itDept?->id,
            'name' => 'Super Administrator',
            'email' => 'admin@lampost.co.id',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // 2. Staff IT (Agung - Sesuai gambar referensi)
        User::updateOrCreate(['email' => 'agung@lampost.co.id'], [
            'department_id' => $itDept?->id,
            'name' => 'Agung H Hyattdi',
            'email' => 'agung@lampost.co.id',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // 3. Manager Umum
        User::updateOrCreate(['email' => 'manager.umum@lampost.co.id'], [
            'department_id' => $umumDept?->id,
            'name' => 'Manajer Umum & SDM',
            'email' => 'manager.umum@lampost.co.id',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        // 4. Staff Keuangan
        User::updateOrCreate(['email' => 'staff.keu@lampost.co.id'], [
            'department_id' => $keuDept?->id,
            'name' => 'Staff Keuangan',
            'email' => 'staff.keu@lampost.co.id',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
    }
}
