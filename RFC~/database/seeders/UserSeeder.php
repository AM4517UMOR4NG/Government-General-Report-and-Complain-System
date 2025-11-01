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
        // Custom requested users
        \App\Models\User::updateOrCreate(
            ['email' => 'admingg@gmail.com'],
            [
                'name' => 'Admin GG',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Will be attached to first department if exists
        $firstDepartmentId = optional(\App\Models\Department::first())->id;
        \App\Models\User::updateOrCreate(
            ['email' => 'departhead@gmail.com'],
            [
                'name' => 'Department Head',
                'password' => bcrypt('password'),
                'role' => 'department_head',
                'department_id' => $firstDepartmentId,
                'is_active' => true,
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'usergg@gmail.com'],
            [
                'name' => 'User GG',
                'password' => bcrypt('password'),
                'role' => 'citizen',
                'is_active' => true,
            ]
        );

        // Specific staff user requested
        \App\Models\User::updateOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff GG',
                'password' => bcrypt('password'),
                'role' => 'staff',
                'department_id' => $firstDepartmentId,
                'is_active' => true,
            ]
        );

        // Create admin user (idempotent)
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@government.gov'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'phone' => '+1-555-0000',
                'address' => 'Government Building, Admin Office',
                'id_number' => 'ADMIN001',
                'is_active' => true,
            ]
        );

        // Create department heads
        $departments = \App\Models\Department::all();
        
        foreach ($departments as $index => $department) {
            \App\Models\User::updateOrCreate([
                'email' => 'head.' . strtolower($department->code) . '@government.gov',
            ], [
                'name' => $department->name . ' Head',
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
                \App\Models\User::updateOrCreate([
                    'email' => 'staff' . $i . '.' . strtolower($department->code) . '@government.gov',
                ], [
                    'name' => 'Staff Member ' . $i . ' - ' . $department->name,
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
            \App\Models\User::updateOrCreate([
                'email' => 'citizen' . $i . '@example.com',
            ], [
                'name' => 'Citizen ' . $i,
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
