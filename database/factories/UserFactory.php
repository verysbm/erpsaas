<?php

namespace Database\Factories;

use App\Models\Setting\CompanyProfile;
use App\Models\{Company, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Wallo\FilamentCompanies\Features;

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
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_company_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal company.
     */
    public function withPersonalCompany(): static
    {
        if (! Features::hasCompanyFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Company::factory()
                ->has(CompanyProfile::factory(), 'profile')
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name . '\'s Company', 'user_id' => $user->id, 'personal_company' => true];
                }),
            'ownedCompanies'
        );
    }
}
