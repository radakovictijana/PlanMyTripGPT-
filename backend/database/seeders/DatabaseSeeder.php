<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;
use App\Models\DailyPlan;
use App\Models\TravelPlan;
use Carbon\Carbon;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        User::factory(10)->create();
        $akt = Activity::create(['name'=>'Obilazak grada','type'=>'kultura']);
        $akt = Activity::create(['name'=>'Nocna setnja','type'=>'kultura']);
        $akt = Activity::create(['name'=>'Voznja brodom','type'=>'kultura']);
        
        $putovanje = DailyPlan::create(['day'=>3,'travel_plan_id' => 3,'description'=>'Rucak ce se sluziti na izletu','activity_id'=>3]);
     
        //User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //]);
        // $this->call(PutovanjeSeeder::class);
        // $this->call(PlanSeeder::class);
       //  $this->call(AktivnostSeeder::class);


    }
}
