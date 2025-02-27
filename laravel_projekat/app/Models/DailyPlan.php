<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyPlan extends Model
{
    use HasFactory;

    protected $fillable =[
        'day',
        'description',
        //'aktivnost',
        'activity',
        'travel_plan_id',
    ];

    public function activities()
    {
        return $this->belongsTo(Activity::class);
        
        
    }
    public function travel_plans()
    {
        return $this->belongsTo(TravelPlan::class,'travel_plan_id');
    }
    
}
