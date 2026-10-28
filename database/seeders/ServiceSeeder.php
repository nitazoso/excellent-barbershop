<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Skin Fade, Classic Scissor & Beard',
                'description' => 'Pangkas presisi dengan teknik scissor dan fade modern, lengkap dengan perawatan jenggot dan kumis.',
                'price' => 285000,
                'duration_minutes' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Executive Pompadour & Hot Towel',
                'description' => 'Gaya pompadour klasik dengan handuk hangat dan ritual botani.',
                'price' => 325000,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Semir Rambut, Texture Crop & Perm',
                'description' => 'Perawatan warna rambut, potongan crop modern, dan layanan perm.',
                'price' => 420000,
                'duration_minutes' => 90,
                'is_active' => true,
            ],
        ];
        foreach ($services as $data) {
            Service::updateOrCreate(['name' => $data['name']], $data);
        }
    }
}
