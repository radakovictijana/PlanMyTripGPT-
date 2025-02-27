<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'destination',
        'start_date',
        'end_date',
        'guide',

        
    ];
    public function daily_plans()
    {
        return $this->hasMany(DailyPlan::class,'travel_plan_id');
    }
    public function user(){
        return $this->belogsTo(User::class);
    }
}

