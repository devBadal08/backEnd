<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function listUsers()
    {
        $users = User::where('role', 'user')->get();

        return response()->json($users);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        // $minAge = $request->query('min_age');
        // $birthYear = $request->query('birth_year');
        // $gender = $request->query('gender');
        // $village = $request->query('village');
        // $city = $request->query('city');
        // $state = $request->query('state');
        $searchTerm = $request->query('keyword');

        $usersQuery = User::where('created_by', auth()->user()->id);

        if (isset($searchTerm) && !empty($searchTerm)) {
            $usersQuery->filterBySearch($searchTerm);
        }
        // if (isset($minAge) && !empty($minAge)) {
        //     $usersQuery->filterByAge($minAge);
        // }
        // if (isset($birthYear) && !empty($birthYear)) {
        //     $usersQuery->filterByBirthYear($birthYear);
        // }
        // if (isset($gender) && !empty($gender)) {
        //     $usersQuery->filterByGender($gender);
        // }
        // if (isset($village) && !empty($village)) {
        //     $usersQuery->filterByLocation($village, $city, $state);
        // }
        // if (isset($city) && !empty($city)) {
        //     $usersQuery->filterByLocation($village, $city, $state);
        // }
        // if (isset($state) && !empty($state)) {
        //     $usersQuery->filterByLocation($village, $city, $state);
        // }

        return UserResource::collection($usersQuery->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Permission for user
        $profile_list = Permission::where(['name' => 'profile.list'])->first();

        $user = new User();

        //validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|digits:10',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password', // Add password validation
            // 'image' => 'required|mimes:jpeg,jpg,png,gif|max:500' //image validation
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //store user
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone = $request->phone;

        //UPLOAD IMAGE
        // $imageName = time() . '.' . $request->image->extension();
        // $request->image->move(public_path('images/users'), $imageName);
        // $user->image = $imageName;   //store image name

        $user->role = 'user';
        $user->created_by = Auth::id();
        $user->save();

        $user->assignRole('user');
        $user->givePermissionTo([
            $profile_list,
        ]);

        return response()->json($user);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        // Check if the user with the provided ID exists
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        // Check if the user with the provided ID exists
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    // This is the function to update the user by Manager
    public function user_update(Request $request, $id)
    {
        $user = auth()->user();

        //Validation
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

        // Check if the user with the provided ID exists
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $postParams = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        // if (isset($request->image)) {
        //     $imageName = time() . '.' . $request->image->extension();
        //     $request->image->move(public_path('images/users'), $imageName);
        //     $user->image = $imageName;
        // }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->update($postParams);
        return new UserResource($user);
    }

    //This is the function to update the user by user itself
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        //Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:10',
            // 'alt_phone' => 'nullable|numeric|digits:10',
            'password' => 'nullable|confirmed|min:8', // Password is optional, but if provided, needs confirmation and minimum length
            'password_confirmation' => 'nullable|required_with:password', // Confirmation required only if password is provided
            // 'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500',
            // Add other fields if needed
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        // Check if the user with the provided ID exists
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $postParams = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'alt_phone' => $request->alt_phone,
            // 'image' => $request->image,
        ];

        //Store the image
        // if (isset($request->image)) {
        //     $imageName = time() . '.' . $request->image->extension();
        //     $request->image->move(public_path('images/users'), $imageName);
        //     $user->image = $imageName;
        // }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->update($postParams);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Check if the user with the provided ID exists
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'user deleted successfully']);
    }
}
