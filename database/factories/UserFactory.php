<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Jabatan;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Nomor WhatsApp default untuk semua user dummy (memudahkan testing
     * notifikasi WA — seluruh pesan uji masuk ke satu nomor).
     */
    public const DEFAULT_WA_NUMBER = '6282280514945';

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['L', 'P']);

        return [
            'uuid' => (string) Str::uuid(),
            'nip' => fake()->unique()->numerify('19##############'),
            'nama' => fake($gender === 'L' ? 'id_ID' : 'id_ID')->firstName($gender).' '.fake('id_ID')->lastName(),
            'gelar_depan' => null,
            'gelar_belakang' => fake()->randomElement([null, 'S.Kep', 'A.Md.Kep', 'S.T', 'A.Md']),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => self::DEFAULT_WA_NUMBER,
            'jenis_kelamin' => $gender,
            'status' => 'active',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Attach the user to a unit (instalasi/departemen).
     */
    public function assignedTo(Unit $unit): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    /**
     * Attach the user to a jabatan (berpengaruh pada role group & akses menu).
     */
    public function withJabatan(Jabatan $jabatan): static
    {
        return $this->state(fn (array $attributes) => [
            'jabatan_id' => $jabatan->id,
        ]);
    }

    /**
     * Assign a Spatie role to the user after creation.
     */
    public function withRole(string $role): static
    {
        return $this->afterCreating(fn (User $user) => $user->syncRoles([$role]));
    }
}
