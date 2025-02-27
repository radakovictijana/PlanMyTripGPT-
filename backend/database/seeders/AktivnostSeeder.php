<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AktivnostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('activities')->insert([

           ['naziv'=> 'Obilazak Koloseuma',
            'destinacija'=> 'Rim',
            'tip' => 'kultura',
            ],
            ['naziv'=> 'Obilazak Vatikana',
            'destinacija'=>'Rim',
            'tip'=>'kultura',
        ]
    ]);
    }
}
