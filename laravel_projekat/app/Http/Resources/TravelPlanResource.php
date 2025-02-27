<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\DailyPlanResource;
use App\Http\Resources\UserResource;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'destination'=>$this->destination,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'guide'=>$this->guide,
            'user'=> new UserResource($this->whenLoaded('user')),
            'daily_plans' => DailyPlanResource::collection($this->whenLoaded('daily_plans')),

           
        ];
    }
}
