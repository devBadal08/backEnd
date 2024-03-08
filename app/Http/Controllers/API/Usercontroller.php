<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $users = User::where('created_by', auth()->user()->id)->get();
        return response()->json($users);
        $users = User::paginate(2);

    }
    /*
    public function index()
    {
        //
      
        $users = User::get();

        return response()->json($users);
    } */



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
        // dd($request->user()->id);
        // Permission for user
        $user_list = Permission::where(['name' => 'user.list'])->first();

        $user = new User();

        //validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'c_password' => 'required|same:password', // Add password validation
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:500' //image validation
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //store user
        $user->first_name = $request->first_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        //UPLOAD IMAGE
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images/users'), $imageName);
        // print_r($img); exit;
        $user->image = $imageName;   //store image name
        $user->role = 'user';
        $user->created_by = Auth::id();
        $user->save();

        $user->assignRole('user');
        $user->givePermissionTo([
            $user_list,
        ]);

        return response()->json($user);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {

        $user = User::where('id', $id)->first();
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        return response()->json($user);
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
            'password' => 'nullable',
            'c_password' => 'same:password',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500'

            // Add other fields as needed
        ]);
        if (isset($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/users'), $imageName);
            $user->image = $imageName;
        }
        // print_r($request); exit;
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $user = User::findd($id);
        $user->update($request->all());

        return response()->json($user, 200);
        // print_r($id);exit;
        // echo"here"; exit();
    }


    //Validation
    /*
         $request->validate([
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'alt_phone' => 'nullable',
            'email' => 'required',
            'username' => 'required',
            'marital_status' => 'required',
            'height' => 'nullable',
            'weight' => 'nullable',
            'hobbies' => 'nullable',
            'about_yourself' => 'nullable',
            'about_job' => 'nullable',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:2000',

            //educational details
            'degree' => 'nullable',
            'organization' => 'nullable',
            'passing_year' => 'nullable',
            'higher_sec' => 'nullable',
        ]); 

        $user = User::where('id',$id)->first();

        if(isset($request->image)){
            //upload image
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('users'),$imageName);
        $user->image = $imageName;  

        
        
        //dd($imageName);
        $user->name = $request->name;
        $user->description = $request->description;

        $user->save();
        return back()->withSuccess('User Updated!!');   
    }      */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // echo "here"; exit;
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'Profile deleted successfully']);
        // return back()->withSuccess('User Deleted!!');
    }
}
