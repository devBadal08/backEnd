<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use Spatie\Permission\Models\Permission;



class ManagerController extends Controller
{
    public function index()
    {
        $managers = User::where('role', 'manager')->paginate(10);
        return response()->json($managers);
    }

    public function store(Request $request)
    {
        //user Permitions for manager
        $user_list = Permission::where(['name' => 'user.list'])->first();
        $user_view = Permission::where(['name' => 'user.view'])->first();
        $user_create = Permission::where(['name' => 'user.create'])->first();
        $user_update = Permission::where(['name' => 'user.update'])->first();
        $user_delete = Permission::where(['name' => 'user.delete'])->first();


        $manager = new User();

        //validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password', // Add password validation
            'phone' => 'required|numeric|digits:10',
            // 'image' => 'required|mimes:jpeg,jpg,png,gif|max:500' //image validation
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //store manager details
        $manager->first_name = $request->first_name;
        $manager->last_name = $request->last_name;
        $manager->email = $request->email;
        $manager->password = bcrypt($request->password);
        $manager->phone = $request->phone;
        //UPLOAD IMAGE
        // $imageName = time() . '.' . $request->image->extension();
        // $request->image->move(public_path('images/managers'), $imageName);
        // print_r($img); exit;
        // $manager->image = $imageName;   //store image name
        $manager->role = 'manager';
        $manager->save();

        $manager->assignRole('manager');
        $manager->givePermissionTo([
            $user_create,
            $user_list,
            $user_update,
            $user_view,
            $user_delete

        ]);

        return response()->json($manager);
    }

    public function show(Request $request, $id)
    {

        $manager = User::where('id', $id)->first();
        return response()->json($manager);
    }

    public function edit(Request $request, $id)
    {
        $manager = User::find($id);
        return response()->json($manager);
    }

    // Manager updated by admin
    public function manager_update(Request $request, $id)
    {
        $manager = auth()->user();
    
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id)
            ],
            'password' => 'nullable|confirmed|min:8', // Password is optional, but if provided, needs confirmation and minimum length
            'password_confirmation' => 'nullable|required_with:password', // Confirmation required only if password is provided
            // 'image' => 'required|mimes:jpeg,jpg,png,gif|max:500' //image validation
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $manager = User::find($id);
        $postParams = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'password_confirmation' => 'same:password',
            // 'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500',
        ];

        // if (isset($request->image)) {
        //     $imageName = time() . '.' . $request->image->extension();
        //     $request->image->move(public_path('images/managers'), $imageName);
        //     $manager->image = $imageName;
        // }

        if ($request->has('password')) {
            $manager->password = bcrypt($request->password);
        }

        $manager->update($postParams);

        return response()->json($manager, 200);
    }

    public function update(Request $request, $id)  //Manager Update itself
    {

        $manager = auth()->user();
        // dd($user);
        // echo "here"; exit;
        // print_r($user); exit;
        // Define validation rules considering required password
        $validator = Validator::make($request->all(), [

            // $validator = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'alt_phone' => 'nullable',
            'password' => 'nullable|confirmed|min:8', // Password is optional, but if provided, needs confirmation and minimum length
            'password_confirmation' => 'nullable|required_with:password', // Confirmation required only if password is provided
            'username' => 'required',
            'marital_status' => 'required',
            'village' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'height' => 'nullable',
            'weight' => 'nullable',
            'hobbies' => 'nullable',
            'about_self' => 'nullable',
            'about_job' => 'nullable',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500', //image validation
            'education' => 'nullable',

            // Add other fields as needed
        ]);
        $dob = $request->dob;

        // print_r($request); exit;
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $manager = User::find($id);

        $postParams = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'alt_phone' => $request->alt_phone,
            'username' => $request->username,
            'marital_status' => $request->marital_status,
            'height' => $request->height,
            'weight' => $request->weight,
            'hobbies' => $request->hobbies,
            'about_self' => $request->about_self,
            'about_job' => $request->about_job,
            'image' => $request->image,
            'education' => $request->education,
            'village' => $request->village,
            'city' => $request->city,
            'state' => $request->state,
        ];
        $age = \Carbon\Carbon::parse($dob)->diff(\Carbon\Carbon::now())->format('%y years');
        $postParams['age'] = $age;

        if (isset($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/managers'), $imageName);
            $manager->image = $imageName;
        }

        if ($request->has('password')) {
            $manager->password = bcrypt($request->password);
        }

        $manager->update($postParams);

        return response()->json($manager, 200);
        // print_r($id);exit;
        // echo"here"; exit();
    }
}
