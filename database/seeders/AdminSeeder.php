<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rawEmails = config('services.admin.emails') ?? env('ADMIN_EMAILS', '');

        $emails = array_values(array_filter(
            array_map('trim', explode(',', (string) $rawEmails))
        ));

        foreach ($emails as $email) {
            $name = Str::headline(Str::before($email, '@'));

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name !== '' ? $name : 'Owner',
                    'role' => 'owner',
                    'is_active' => true,
                ]
            );
        }
    }
}
