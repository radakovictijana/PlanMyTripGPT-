<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\TravelPlanController; 
use App\Models\TravelPlan;



class ImageController extends Controller
{
    public function upload(Request $request)
    {
        // Validacija
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimalna veličina 2MB
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        // Čuvanje slike
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images', $imageName, 'public');
            
            // Putanja slike za frontend
            $travelId = $request->input('id');
            $filePath = Storage::url($path);
            $travel = TravelPlan::find($travelId);
            $travel->picture=$filePath;
            $travel->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Image uploaded successfully',
                'file_path' => $filePath,
            ], 200);
        }
        

        return response()->json([
            'status' => 'error',
            'message' => 'Image upload failed',
        ], 500);
    }
}
