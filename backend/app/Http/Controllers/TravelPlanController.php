<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Auth;
use App\Models\TravelPlan;
use App\Models\DailyPlan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Resources\TravelPlanResource;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class TravelPlanController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $travels = TravelPlan::all();
        
        return response()->json($travels);
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

       

       
        return $travelPlan;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $travel =TravelPlan::findOrFail($id);
        
        return response()->json($travel);
    }

    public function exportPdf()
    {
      
        $pdf = Pdf::loadView('pdf.template', ['data' => $data]);

        return $pdf->download('data.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TravelPlan $putovanje)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $travel = TravelPlan::findOrFail($id);
        $travel->user_id=auth()->id();
        $travel->destination = $request->destination;
        $travel->start_date = $request->start_date;
        $travel->end_date = $request->end_date;

        $travel->save();
        return response()->json(['message'=>'Travel updated successfully!','data'=>$travel]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $travel=TravelPlan::findOrFail($id);
        $travel->delete();
        return response(['message'=>'Travel deleted successfully!']);
    }
    
    public function search(Request $request)
    {
        $searchTerm = $request->input('query');

        $travels = TravelPlan::where('destination', 'LIKE', "%{$searchTerm}%")->get();
        
        return response()->json(['message'=>'Uspesno','data'=>$travels]);
    }

    public function filter(Request $request) 
    {
        $startYear = $request->input('start_year');
        $endYear = $request->input('end_year');  
        $hasGuide = $request->input('has_guide');  

        $query = TravelPlan::query();

        if ($startYear) {
            $query->whereYear('start_date', $startYear); 
        }

        if ($endYear) {
            $query->whereYear('end_date', $endYear); 
        }

        if ($startYear && $endYear && $startYear > $endYear) {
            return response()->json(['error' => 'Start year cannot be greater than end year.'], 400);
        }

        if (!is_null($hasGuide)) {
            $query->where('has_guide', $hasGuide == 'true' ? 1 : 0);
        }

        

        
        $travels = $query->get();

        return response()->json($travels);
    }
    public function showPlans($id)
    {
            // Find the travel plan by its ID
        $travel = TravelPlan::with('daily_plans')->find($id);  
        //$travel = TravelPlan::with('daily_plans')->where('id', $id)->first();
      
        if (!$travel) {
            return response()->json(['message' => 'Travel plan not found'], 404);
        }
        
        // Return the travel plan along with its daily plans using a resource
        //dd($travel->daily_plans);
        return new TravelPlanResource($travel);
    }
        
    
}
