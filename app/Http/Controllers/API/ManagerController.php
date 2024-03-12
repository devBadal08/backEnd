<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Spatie\Permission\Models\Permission;



class ManagerController extends Controller
{
    public function index()
    {
        $managers = User::where('role', 'manager')->paginate(1);
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
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'c_password' => 'required|same:password', // Add password validation
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:500' //image validation
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //store manager
        $manager->first_name = $request->first_name;
        $manager->email = $request->email;
        $manager->password = bcrypt($request->password);
        //UPLOAD IMAGE
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images/managers'), $imageName);
        // print_r($img); exit;
        $manager->image = $imageName;   //store image name
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

    public function update(Request $request, $id)
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
            'password' => 'nullable',
            'c_password' => 'same:password',
            'username' => 'required',
            'marital_status' => 'required',
            'height' => 'nullable',
            'weight' => 'nullable',
            'hobbies' => 'nullable',
            'about_self' => 'nullable',
            'about_job' => 'nullable',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500', //image validation
            'education' => 'nullable', 
            // Add other fields as needed
        ]);

        if (isset($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/managers'), $imageName);
            $manager->image = $imageName;
        }

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
            'password' => $request->password,
            'c_password' => $request->c_password,
            'username' => $request->username,
            'marital_status' => $request->marital_status,
            'height' => $request->height,
            'weight' => $request->weight,
            'hobbies' => $request->hobbies,
            'about_self' => $request->about_self,
            'about_job' => $request->about_job,
            'image' => $request->image,
            'education' => $request->education,
        ];
        $age = \Carbon\Carbon::parse($dob)->diff(\Carbon\Carbon::now())->format('%y years');
        $postParams['age'] = $age;
        $manager->update($postParams);

        return response()->json($manager, 200);
        // print_r($id);exit;
        // echo"here"; exit();
    }
}
