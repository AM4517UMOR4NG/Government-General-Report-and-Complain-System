<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::create([
            'name' => 'System Administrator',
            'email' => 'admin@government.gov',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'phone' => '+1-555-0000',
            'address' => 'Government Building, Admin Office',
            'id_number' => 'ADMIN001',
            'is_active' => true,
        ]);

        // Create department heads
        $departments = \App\Models\Department::all();
        
        foreach ($departments as $index => $department) {
            \App\Models\User::create([
                'name' => $department->name . ' Head',
                'email' => 'head.' . strtolower($department->code) . '@government.gov',
                'password' => bcrypt('head123'),
                'role' => 'department_head',
                'department_id' => $department->id,
                'phone' => '+1-555-' . str_pad(1000 + $index, 4, '0', STR_PAD_LEFT),
                'address' => $department->address,
                'id_number' => 'HEAD' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'is_active' => true,
            ]);
        }

        // Create sample staff members
        foreach ($departments as $index => $department) {
            for ($i = 1; $i <= 3; $i++) {
                \App\Models\User::create([
                    'name' => 'Staff Member ' . $i . ' - ' . $department->name,
                    'email' => 'staff' . $i . '.' . strtolower($department->code) . '@government.gov',
                    'password' => bcrypt('staff123'),
                    'role' => 'staff',
                    'department_id' => $department->id,
                    'phone' => '+1-555-' . str_pad(2000 + ($index * 3) + $i, 4, '0', STR_PAD_LEFT),
                    'address' => 'Staff Address ' . $i,
                    'id_number' => 'STAFF' . str_pad(($index * 3) + $i, 3, '0', STR_PAD_LEFT),
                    'is_active' => true,
                ]);
            }
        }

        // Create sample citizens
        for ($i = 1; $i <= 10; $i++) {
            \App\Models\User::create([
                'name' => 'Citizen ' . $i,
                'email' => 'citizen' . $i . '@example.com',
                'password' => bcrypt('citizen123'),
                'role' => 'citizen',
                'phone' => '+1-555-' . str_pad(3000 + $i, 4, '0', STR_PAD_LEFT),
                'address' => 'Citizen Address ' . $i,
                'id_number' => 'CIT' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'is_active' => true,
            ]);
        }
    }
}
