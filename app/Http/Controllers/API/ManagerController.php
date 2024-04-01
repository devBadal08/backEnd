<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Http\Resources\UserResource;
use Spatie\Permission\Models\Permission;

class ManagerController extends Controller
{
    //List the all managers
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $searchTerm = $request->query('keyword');

        $managersQuery = User::where('role', 'manager')
            ->with('profiles');

        if (isset($searchTerm) && !empty($searchTerm)) {
            $managersQuery->filterBySearch($searchTerm);
        }

        return UserResource::collection($managersQuery->paginate($perPage));

    }

    public function store(Request $request)
    {
        //user Permission for manager
        $user_list = Permission::where(['name' => 'user.list'])->first();
        $user_view = Permission::where(['name' => 'user.view'])->first();
        $user_create = Permission::where(['name' => 'user.create'])->first();
        $user_update = Permission::where(['name' => 'user.update'])->first();
        $user_delete = Permission::where(['name' => 'user.delete'])->first();
        $profile_list = Permission::where(['name' => 'profile.list']);
        $profile_view = Permission::where(['name' => 'profile.view']);
        $profile_create = Permission::where(['name' => 'profile.create']);
        $profile_update = Permission::where(['name' => 'profile.update']);
        $profile_delete = Permission::where(['name' => 'profile.delete']);

        $manager = new User();

        //validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password', // Add password validation
            'phone' => 'required|numeric|digits:10',
            'max_profiles_limit' => 'required|numeric',
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
        $manager->max_profiles_limit = $request->max_profiles_limit;

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
            $user_delete,
            $profile_list,
            $profile_view,
            $profile_create,
            $profile_update,
            $profile_delete,

        ]);
        return new UserResource($manager);
    }

    public function show(Request $request, $id)
    {
        // Check if the manager with the provided ID exists
        $manager = User::find($id);

        if (!$manager) {
            return response()->json(['error' => 'manager not found'], 404);
        }

        return new UserResource($manager);
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
            'phone' => 'required|numeric|digits:10',
            'max_profiles_limit' => 'required|numeric',
            // 'image' => 'required|mimes:jpeg,jpg,png,gif|max:500' //image validation
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        // Check if the manager with the provided ID exists
        $manager = User::find($id);

        if (!$manager) {
            return response()->json(['error' => 'manager not found'], 404);
        }

        $postParams = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'max_profiles_limit' => $request->max_profiles_limit,
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
        return new UserResource($manager);
    }

    //Manager Update itself
    public function update(Request $request, $id)
    {
        $manager = auth()->user();

        //Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required',
            // 'alt_phone' => 'nullable',
            'password' => 'nullable|confirmed|min:8', // Password is optional, but if provided, needs confirmation and minimum length
            'password_confirmation' => 'nullable|required_with:password', // Confirmation required only if password is provided
            // 'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500', //image validation
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        // Check if the profile with the provided ID exists
        $manager = User::find($id);

        if (!$manager) {
            return response()->json(['error' => 'manager not found'], 404);
        }

        $postParams = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            // 'alt_phone' => $request->alt_phone,
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

        return new UserResource($manager);
    }
}
