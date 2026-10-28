<?php

namespace Database\Seeders;

use App\Models\Barber;
use Illuminate\Database\Seeder;

class BarberSeeder extends Seeder
{
    public function run(): void
    {
        $barbers = [
            [
                'name' => 'Mas Dimas',
                'photo' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=500&q=80',
                'description' => 'Senior Barber · Master Barber',
                'is_active' => true,
            ],
            [
                'name' => 'Budi Santoso',
                'photo' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=500&q=80',
                'description' => 'Senior Barber',
                'is_active' => true,
            ],

        ];
        foreach ($barbers as $data) {
            Barber::updateOrCreate(['name' => $data['name']], $data);
        }
    }
}
