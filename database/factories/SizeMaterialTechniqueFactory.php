<?php

namespace Database\Factories;

use App\Models\SizeMaterialTechnique;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SizeMaterialTechniqueFactory extends Factory
{
    protected $model = SizeMaterialTechnique::class;

    public function definition()
    {
        return [
			'size_id' => $this->faker->name,
			'material_technique_id' => $this->faker->name,
        ];
    }
}
