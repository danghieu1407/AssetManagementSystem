<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->name();
        $location = ['HCM', 'HN', 'DN'];
        $userName = strtolower( $firstName) . $this->takeTheFirstCharacter($lastName);
        $gender = [0,1,null];
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'date_of_birth' => $this->faker->date('Y-m-d', "-18 years"),
            'joined_date' => $this->faker->dateTimeBetween('-17 years', "now")
                ->format('Y-m-d'),
            'username' => $userName,
            'password' => bcrypt('12345'),
            'admin' => rand(0, 1),
            'location' => ($location[rand(0, 2)]),
            'state' => rand(-1, 1),
            'gender'=>$gender[rand(0,2)]
        ];
    }

    public function takeTheFirstCharacter($string)
    {
        $newArr = explode(" ", $string);
        $result = '';
        foreach ($newArr as $item) {
            $result .= strtolower( $item[0]);
        }
        return $result;
    }
}
