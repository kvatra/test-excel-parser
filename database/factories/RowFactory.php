<?php

namespace Database\Factories;

use App\Models\Row;
use Illuminate\Database\Eloquent\Factories\Factory;

class RowFactory extends Factory
{
    protected $model = Row::class;

    public function definition()
    {
        return [
            'id' => $this->faker->randomNumber(),
            'name' => $this->faker->name,
            'date' => $this->faker->date(),
            'file_id' => $this->faker->uuid(),
        ];
    }
}
