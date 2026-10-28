<?php

namespace Database\Seeders;

use App\Models\Barber;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $barbers = Barber::query()->where('is_active', true)->get();

        $sessions = [
            ['start' => '09:00', 'end' => '12:00'],
            ['start' => '13:00', 'end' => '17:00'],
            ['start' => '18:00', 'end' => '21:00'],
            ['start' => '19:30', 'end' => '21:30'],
        ];

        $now = now();
        $start = $now->copy()->startOfDay();
        $end = $now->copy()->addDays(7)->endOfDay();

        $barbers->each(function (Model $barber) use ($sessions, $start, $end) {
            $date = $start->copy();
            while ($date <= $end) {
                foreach ($sessions as $session) {
                    Schedule::updateOrCreate(
                        [
                            'barber_id' => $barber->id,
                            'date' => $date->format('Y-m-d'),
                            'start_time' => $session['start'],
                        ],
                        [
                            'end_time' => $session['end'],
                            'is_available' => true,
                        ]
                    );
                }
                $date->addDay();
            }
        });
    }
}
