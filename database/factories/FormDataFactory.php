<?php

namespace Database\Factories;

use App\Models\FormData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormData>
 */
class FormDataFactory extends Factory
{
    protected $model = FormData::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Test Form Data'
        ];
    }
}
