<?php

namespace Database\Factories;

use App\Models\MaterialTechnique;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MaterialTechniqueFactory extends Factory
{
    protected $model = MaterialTechnique::class;

    public function definition()
    {
        return [
			'technique_id' => $this->faker->name,
			'material_id' => $this->faker->name,
        ];
    }
}
