<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['code' => 'RED', 'name' => 'Redaksi & Editor'],
            ['code' => 'IT', 'name' => 'Medsos & IT Departemen'],
            ['code' => 'KEU', 'name' => 'Keuangan & Akuntansi'],
            ['code' => 'UMUM', 'name' => 'Manajer Umum & SDM'],
            ['code' => 'IKLAN', 'name' => 'Pemasaran & Iklan'],
            ['code' => 'SIRK', 'name' => 'Sirkulasi & Distribusi'],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
