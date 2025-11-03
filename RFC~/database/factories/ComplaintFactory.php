<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintFactory extends Factory
{
    protected $model = Complaint::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'department_id' => Department::factory(),
            'ticket_no' => 'COM-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['Pelayanan', 'Fasilitas', 'Staff', 'Prosedur', 'Lainnya']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => 'submitted',
            'assigned_to' => null,
        ];
    }

    /**
     * Indicate that the complaint is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'verified',
        ]);
    }

    /**
     * Indicate that the complaint is assigned.
     */
    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'assigned',
            'assigned_to' => User::factory(),
        ]);
    }

    /**
     * Indicate that the complaint is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'assigned_to' => User::factory(),
        ]);
    }

    /**
     * Indicate that the complaint is resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
        ]);
    }
}
