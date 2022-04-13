<?php

namespace Database\Factories;

use App\Models\PricesTechnique;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PricesTechniqueFactory extends Factory
{
    protected $model = PricesTechnique::class;

    public function definition()
    {
        return [
			'size_material_technique_id' => $this->faker->name,
			'escala_inicial' => $this->faker->name,
			'escala_final' => $this->faker->name,
			'precio' => $this->faker->name,
			'tipo_precio' => $this->faker->name,
        ];
    }
}
