<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;




class PutovanjeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datumDolaska = Carbon::createFromFormat('d.m.Y', '27.07.2024')->format('Y-m-d');
        $datumPolaska = Carbon::createFromFormat('d.m.Y', '23.07.2024')->format('Y-m-d');
        DB::table('putovanje')->insert([
            ['destinacija'=> 'Rim',
            'datumPolaska'=> $datumPolaska,
            'datumDolaska'=> $datumDolaska,
            
            ],
            ['destinacija'=> 'Atina',
            'datumPolaska'=> $datumPolaska,
            'datumDolaska'=> $datumDolaska,
            
            ],
        ]);
    }
}
