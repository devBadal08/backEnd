<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
//  use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::get();

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
    public function show(Request $request ,$id)
    {
        $user = auth()->user();
        $user = User::where('id',$id)->first();
        return response()->json($user); 

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, Request $request)
    {
        // echo"here";exit;
        $user = User::find($id);
        print_r($id);exit;
        return response()->json($user);
    //     $user = User::where('id', $id)->first();
    //     return response()->json(auth()->$user());
    //     // return view('products.edit', ['product' => $product]);

     }

    /**
     * Update the specified resource in storage.
     */
    

        public function update(Request $request, $id)
        {
            $user = auth()->user();
        // dd($user);
        // echo "here"; exit;
        // print_r($user); exit;
        // Define validation rules considering required password
        $validator = Validator::make($request->all(), [

            // $validator = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password'

            // Add other fields as needed
        ]);
        // print_r($request); exit;
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $user = User::find($id);
        $user->update($request->all());

        return response()->json($user, 200);
        // print_r($id);exit;
        // echo"here"; exit();

    //     $user = Auth::user();

    //     $validatedData = $request->validate([
    //         'name' => 'string|max:255',
    //         'email' => 'email|unique:users,email,' . $user->id,
    //         'password' => 'nullable|string|min:6|confirmed',
    //     ]);

    //     // Update user profile
    //     $user = User::find($id);
    //     $user->update([
    //         'name' => $validatedData['name'],
    //         'email' => $validatedData['email'],
    //         'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : $user->password,
    //     ]);

    //     return response()->json(['message' => 'Profile updated successfully']);
    // }
    // //     //validation
    // //     $validator = Validator::make($request->all(), [
    // //         'first_name' => 'required',
    // //         'middle_name' => 'required',
    // //         'last_name' => 'required',
    // //         'dob' => 'required',
    // //         'gender' => 'required',
    // //         'phone' => 'required',
    // //         'alt_phone' => 'nullable',
    // //         'email' =>'required|email',
    // //         'username' => 'required',
    // //         'marital_status' => 'required',
    // //         'height' => 'nullable',
    // //         'weight' => 'nullable',
    // //         'hobbies' => 'nullable',
    // //         'about_yourself' => 'nullable',
    // //         'about_job' => 'nullable',
    // //         'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:2000',

    // //         //educational details
    // //         'degree' => 'nullable',
    // //         'organization' => 'nullable',
    // //         'passing_year' => 'nullable',
    // //         'higher_sec' => 'nullable',
    // //     ]);

    // //     $user = User::find($id);
    // //    $user->User::update([
    // //     'first_name'=>$request->first_name,
    // //     'middle_name'=>$request->middle_name,
    // //     'last_name'=>$request->last_name,
    // //     'dob' =>$request->dob,
    // //     'gender' =>$request->gender,
    // //     'phone' =>$request->phone,
    // //     'alt_phone' =>$request->alt_phone,
    // //     'email' =>$request->email,
    // //     'username' =>$request->username,
    // //     'marital_status' =>$request->marital_status,
    // //     'height' =>$request->height,
    // //     'weight' =>$request->weight,
    // //     'hobbies' =>$request->hobbies,
    // //     'about_yourself' =>$request->about_yourself,
    // //     'about_job' =>$request->about_job,
    // //      'image' =>$image,

    //     //educational details
    // //     'degree'  =>$request->degree,
    // //     'organization' =>$request->organization,
    // //     'passing_year'=>$request->passing_year,
    // //     'higher_sec' =>$request->higher_sec,

    // //  ]);
    // //    return response()->json([
    // //     'message' =>'Profile successfully updated',
    // //    ],200);
    // // }

    // /**
    //  * Remove the specified resource from storage.
    }
    public function destroy($id){

        $user = User::find($id);       
        $user->delete();
        return response()->json(['message' => 'Profile deleted successfully']);
             

    }

  
}
