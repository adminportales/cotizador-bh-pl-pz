<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
			'user_id' => $this->faker->name,
			'client_odoo_id' => $this->faker->name,
			'name' => $this->faker->name,
			'contact' => $this->faker->name,
			'email' => $this->faker->name,
			'phone' => $this->faker->name,
        ];
    }
}
