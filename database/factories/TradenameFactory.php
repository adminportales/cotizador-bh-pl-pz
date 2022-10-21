<?php

namespace Database\Factories;

use App\Models\Tradename;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TradenameFactory extends Factory
{
    protected $model = Tradename::class;

    public function definition()
    {
        return [
			'client_id' => $this->faker->name,
			'name' => $this->faker->name,
        ];
    }
}
