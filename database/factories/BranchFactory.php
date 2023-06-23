<?php

namespace Database\Factories;

use App\Models\User;
use Database\Factories\Helpers\FactoryHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   $branches=['IT','HEALTHCARE'];
        $warehouses=[1,2];
        return [
            'name'=>$this->faker->randomElement($branches),
            'warehouse_id'=>$this->faker->randomElement($warehouses),
            'account_id'=>FactoryHelper::getRandomModelId(User::class),
            'profile_logo'=>$this->faker->imageUrl(640, 480, 'branchLog', true),
            'address'=>$this->faker->secondaryAddress(),
            'time'=>null
        ];
    }
}
