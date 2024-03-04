<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Authcontroller extends Controller
{
    public function register(Request $request)
    {
        //echo"here";exit;
        //validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);
       
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            //echo"here";exit;
            return response()->json($response, 400);
        }
        
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        // dd($input);
        $user = User::create($input);

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User register successfully'
        ];
       
     
        return response()->json($response, 200);
        // echo"here";exit;
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])) {
            $user = Auth::user();
            
            $token= $user->createToken('MyApp')->plainTextToken; 
            $success['first_name'] = $user->first_name;

            $response = [
                'success' => true,
                'token' => $token,
                'message' => 'User login successfully',
                'user'=>$user
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Unauthorized'
            ];
            return response()->json($response);
        }
    }
}
