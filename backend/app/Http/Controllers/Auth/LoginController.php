<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=> 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }
        //dd($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>Hash::make($request->password),
        ]);
        
       
        $token =$user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Korisnik uspesno registrovan','user'=>$user,'access_token'=>$token]);

    }
    public function login(Request $request)

    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if(!Auth::attempt($request->only('email','password')))
        {
            return response()->json(['message'=>'Unauthorized'],401);
        }
        $user = User::where('email',$request['email'])->firstOrFail();

        $token =$user->createToken('auth_token')->plainTextToken;


        return response()->json(['access_token'=>$token,'token_type'=>'Bearer', 'user'=>$user]);
       
    

    }
    public function logout(Request $request)
    {
        $user=auth()->user();
        $user->tokens()->delete();
        return response()->json(['logout successfully']);
    }
}
