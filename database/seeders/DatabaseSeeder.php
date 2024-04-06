<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $start = now()->startOfMonth()->subMonthNoOverflow();
        $end = now();

        $period = CarbonPeriod::create($start, '1 day', $end);
        User::factory(5)->create()->each(function ($user) use ($period) {
            foreach ($period as $date) {
                $date->hour(rand(0, 23))->minute(rand(0, 59))->second(rand(0, 59));

                Task::factory(Task::class)->create([
                    'user_id'=> $user->id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        });
    }
}
