<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Public Works Department',
                'description' => 'Handles infrastructure and public works projects',
                'code' => 'PWD',
                'email' => 'pwd@government.gov',
                'phone' => '+1-555-0101',
                'address' => '123 Government Street, City Center',
                'is_active' => true,
            ],
            [
                'name' => 'Health Department',
                'description' => 'Manages public health and medical services',
                'code' => 'HD',
                'email' => 'health@government.gov',
                'phone' => '+1-555-0102',
                'address' => '456 Health Avenue, Medical District',
                'is_active' => true,
            ],
            [
                'name' => 'Education Department',
                'description' => 'Oversees educational institutions and programs',
                'code' => 'ED',
                'email' => 'education@government.gov',
                'phone' => '+1-555-0103',
                'address' => '789 Education Boulevard, School District',
                'is_active' => true,
            ],
            [
                'name' => 'Public Safety Department',
                'description' => 'Ensures public safety and emergency services',
                'code' => 'PSD',
                'email' => 'safety@government.gov',
                'phone' => '+1-555-0104',
                'address' => '321 Safety Plaza, Emergency Center',
                'is_active' => true,
            ],
            [
                'name' => 'Environmental Department',
                'description' => 'Manages environmental protection and sustainability',
                'code' => 'ENV',
                'email' => 'environment@government.gov',
                'phone' => '+1-555-0105',
                'address' => '654 Green Street, Eco District',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            \App\Models\Department::create($department);
        }
    }
}
