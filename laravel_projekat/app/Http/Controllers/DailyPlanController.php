<?php

namespace App\Http\Controllers;

use App\Models\DailyPlan;
use App\Models\TravelPlan;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TravelPlanController; 



class DailyPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function index()
    {
        $plans = DB::table('daily_plans')
        ->join('activities','daily_plans.activity_id','=','activities.id')
        ->select('daily_plans.day')
        ->get();
        $activityList =Activity::select('id','name')->get();


        return compact('plans','activityList');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $plan=new DailyPlan();
        $plan->day=$request->day;
        $plan->travel_plan_id=$request->travel_plan_id;
        $randomActivity = Activity::inRandomOrder()->limit(1)->first();
        $activityid = $randomActivity->id;
        $plan->description=$request->description;
        $plan->activity_id=$activityid;
        $plan->save();
    
        return response()->json(['message'=>'Plan is saved successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $plan =DailyPlan::find($id);
        return response()->json($plan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyPlan $plan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $plan = DailyPlan::findOrFail($id);
        $plan->day = $request->day;
        $plan->travel_plan_id= $request->travel_plan_id;
        $randomActivity = Activity::inRandomOrder()->limit(1)->first();
        $activityid = $randomActivity->id;
        $plan->activity_id=$activityid;       
        $plan->description=$request->description;


        $plan->save();
        return response()->json(['message'=>'Plan updated successfully!']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plan= DailyPlan::findOrFail($id);
        $plan->delete();
        return response()->json(['message'=>'Deleted plan!']);
    }
    public function generateTravelPlan(Request $request)
    {
        $user = auth()->user();
        $user_id=$user->id;
        $request->validate([
            'destination'=>'required|string',
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date',
            'guide'=>'required|string'
        ]);
        if($request->input('guide')=='yes'){
            $guideNum ='1';
        }
        else
        {
            $guideNum='0';
        }
       
        
        //dd($id);
        $travelPlan = TravelPlan::create([
            'user_id'=> auth()->id(),
            'destination' => $request->input('destination'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'guide'=>$guideNum
        ]);

       // $travel = new TravelPlanController();
        //$travel->store($request);
        $userInput = [
            'user_id' => "1",
            'destination' => $request->input('destination'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'guide' => $request->input('guide'),
        ];

        $generatedPlan = $this->openAIService->generateTravelPlan($userInput);
        //dd($generatedPlan);
         // Provera da li postoji odgovor iz OpenAI API-a
         if (!isset($generatedPlan['choices'][0]['message']['content'])) {
            return response()->json(['error' => 'No valid response from OpenAI'], 500);
        }
    
        // Parsiranje JSON stringa iz content polja
        $parsedContent = json_decode($generatedPlan['choices'][0]['message']['content'], true);
         if (!isset($parsedContent['Plans']) || !is_array($parsedContent['Plans'])) {
            return response()->json(['error' => 'No plans found in the generated plan'], 500);
        }
        //dd($parsedContent);
        //dd($travelPlan->id);
        // Sačuvamo plan ishrane u bazu podataka tek kada imamo obroke
        foreach($parsedContent['Plans'] as $plans){
           // dd($travelPlan->id);
            $dailyPlan=DailyPlan::create([
                'description'=>$plans['description'],
                'travel_plan_id'=>$travelPlan->id,
                'activity'=>$plans['activity'],
                'day'=>$plans['day'],
                
            ]);
            $createdPlans[] = $dailyPlan;
        }
    
       
        
    
        // Vraćamo odgovor sa kreiranim planom i obrocima
        return response()->json([ 'plans' => $createdPlans], 201);
    


    }
    public function regenerateDay(Request $request){
        $user = auth()->user();
        $user_id=$user->id;
        $request->validate([
            'day' => 'required|integer',
            'id' => 'required|integer|exists:daily_plans,id', // Proverava da li ID postoji u tabeli
        ]);
        
        $userInput=[
            'day'=> $request->day,
            
        ];
        
       
        $generatedPlan = $this->openAIService->regenerateTravelPlan($userInput);
 
       //dd($generatedPlan);

         // Provera da li postoji odgovor iz OpenAI API-a
    if (!isset($generatedPlan['choices'][0]['message']['content'])) {
            return response()->json(['error' => 'No valid response from OpenAI'], 500);
    }
    
    //    
    $parsedContent = json_decode($generatedPlan['choices'][0]['message']['content'], true);
    if (!isset($parsedContent['Plans']) || !is_array($parsedContent['Plans'])) {
        return response()->json(['error' => 'No plans found in the generated plan'], 500);
    }
    //dd($parsedContent);
       // Preuzimanje plana iz parsedContent
       $planForDay = $parsedContent['Plans'];
        //dd($planForDay);
       // Proverite da li $planForDay sadrži sve potrebne ključeve
       if (!isset($planForDay['day'], $planForDay['description'], $planForDay['activity'])) {
           return response()->json(['error' => 'Invalid plan structure'], 500);
       }
   
       // Pronađite plan koji treba ažurirati na osnovu ID-a iz zahteva
       $plan = DailyPlan::findOrFail($request->id);
   
       // Ažuriranje postojećeg plana sa novim podacima
       $plan->day = $planForDay['day'];
       $plan->description = $planForDay['description'];
       $plan->activity = $planForDay['activity'];
       $plan->save();
    
    return response()->json(['plan' => $plan], 201);
    
    }
}
