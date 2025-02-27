<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\TravelPlan;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $users = Cache::remember('users_all', 60, function () {
            return User::all();
        });
        $users =User::paginate(10);
        return response()->json($users);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
    // public function indexUserTravel(Request $request){

    //     $id = $request->route('id');
    //     $user=User::find($id);
    //     $perPage = $request->input('per_page', 5);
    //     $travels = TravelPlan::where('user_id', $id)->paginate($perPage);
    //     return response()->json($travels);
    // }

    public function indexUserTravel(Request $request)
    {
        $id = $request->route('id');
        $user = User::find($id);
        $startYear = $request->input('start_year');
        $endYear = $request->input('end_year');  
        $hasGuide = $request->input('guide');
        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1);
        $sortOrder = $request->input('sort_order', 'asc'); 

        $query = TravelPlan::where('user_id', $id);

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
            $query->where('guide', $hasGuide == 'true' ? 1 : 0);
        }

        $query->orderBy('destination', $sortOrder);
        $travels = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($travels);
    }

}
