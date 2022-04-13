<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition()
    {
        return [
			'nombre' => $this->faker->name,
			'extras' => $this->faker->name,
			'slug' => $this->faker->name,
        ];
    }
}
