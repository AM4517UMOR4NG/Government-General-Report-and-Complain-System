<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $departments = [
            ['name' => 'Public Works Department', 'code' => 'PWD'],
            ['name' => 'Health Department', 'code' => 'HD'],
            ['name' => 'Education Department', 'code' => 'ED'],
            ['name' => 'Public Safety Department', 'code' => 'PSD'],
            ['name' => 'Environmental Department', 'code' => 'ENV'],
            ['name' => 'Transportation Department', 'code' => 'TD'],
            ['name' => 'Finance Department', 'code' => 'FD'],
        ];

        $dept = $this->faker->randomElement($departments);

        return [
            'name' => $dept['name'],
            'code' => $dept['code'],
            'description' => $this->faker->sentence(),
        ];
    }
}
