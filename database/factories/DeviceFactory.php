<?php

namespace Database\Factories;

use App\Models\Branch;
use Database\Factories\Helpers\FactoryHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'device_name'=>$this->faker->word,
            'serial_number'=>$this->faker->unique()->numerify($this->faker->boolean(8) ? 'SN0221####' : 'SN########'),
            'mac_address'=>$this->faker->unique()->randomElement([
                $this->faker->macAddress(),
                $this->faker->boolean(5) ? '0221:' . $this->faker->regexify('[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}') 
                :  $this->faker->macAddress()
            ]),
            'status'=>$this->faker->boolean(4),
            'branch_id'=>FactoryHelper::getRandomModelId(Branch::class),
            'registered_date'=>$this->faker->dateTime,
            'sold_date' => $this->faker->randomElement([null, $this->faker->boolean(22) ? $this->faker->date : null]),
            'cartoon_number' =>$this->faker->numberBetween(1,5),
        ];
    }
}
