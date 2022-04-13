<?php

namespace Database\Factories;

use App\Models\Technique;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TechniqueFactory extends Factory
{
    protected $model = Technique::class;

    public function definition()
    {
        return [
			'nombre' => $this->faker->name,
			'slug' => $this->faker->name,
        ];
    }
}
