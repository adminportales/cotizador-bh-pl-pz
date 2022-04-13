<?php

namespace Database\Factories;

use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SizeFactory extends Factory
{
    protected $model = Size::class;

    public function definition()
    {
        return [
			'nombre' => $this->faker->name,
			'slug' => $this->faker->name,
        ];
    }
}
