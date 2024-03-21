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
    /**
     * Display a listing of the resource.
     */

    public function listUsers()
    {
        $users = User::where('role', 'user')->get();

        return response()->json($users);
    }

    public function index(Request $request)
    {
        $userQuery = User::where('created_by', auth()->user()->id)->paginate(10);
        // return response()->json($users);

        $perPage = $request->query('per_page', 10);
        $minAge = $request->query('min_age');
        $birthYear = $request->query('birth_year');
        $gender = $request->query('gender');
        $village = $request->query('village');
        $city = $request->query('city');
        $state = $request->query('state');
        $searchTerm = $request->query('keyword');


        if (isset($searchTerm) && !empty($searchTerm)) {
            $userQuery->filterBySearch($searchTerm);
          }
          if (isset($minAge) && !empty($minAge)) {
            $userQuery->filterByAge($minAge);
          }
          if (isset($birthYear) && !empty($birthYear)) {
            $userQuery->filterByBirthYear($birthYear);
          }
          if (isset($gender) && !empty($gender)) {
            $userQuery->filterByGender($gender);
          }
          if (isset($village) && !empty($village)) {
            $userQuery->filterByLocation($village, $city, $state);
        }
        if (isset($city) && !empty($city)) {
            $userQuery->filterByLocation($village, $city, $state);
        }
        if (isset($state) && !empty($state)) {
            $userQuery->filterByLocation($village, $city, $state);
        }

        // if ($minAge || $birthYear || $gender || $village || $city || $state) {
        //     $users = User::filterByAge($minAge)
        //         ->filterByBirthYear($birthYear)
        //         ->filterByGender($gender)
        //         ->filterByLocation($village, $city, $state)
        //         ->paginate($perPage);
        // }

        // return response()->json($userQuery);
        return UserResource::collection($userQuery);
        // return UserResource::collection($userQuery->paginate($perPage));

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
        // dd($request->user()->id);
        // Permission for user
        $profile_list = Permission::where(['name' => 'profile.list'])->first();

        $user = new User();

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
        //store user
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone = $request->phone;
        //UPLOAD IMAGE
        // $imageName = time() . '.' . $request->image->extension();
        // $request->image->move(public_path('images/users'), $imageName);
        // // print_r($img); exit;
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
    public function user_update(Request $request, $id)  // This is the function to update the user by Manager
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
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

        $user = User::find($id);
        $postParams = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'password' => 'nullable|min:8',
            // 'c_password' => 'same:password',
            // 'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500',
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

        return response()->json($user, 200);
    }

    public function update(Request $request, $id)  //This is the function to update the user by user itself
    {

        // print_r($request->all());die();
        $user = auth()->user();
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
            'phone' => 'required|numeric|digits:10',
            'alt_phone' => 'nullable|numeric|digits:10',
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
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500',
            'education' => 'nullable',
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
            // 'image' => $request->image,
            'education' => $request->education,
            'village' => $request->village,
            'city' => $request->city,
            'state' => $request->state,


        ];

        // Calculate the age from DOB and Store it 
        $dob = $request->dob;
        $age = \Carbon\Carbon::parse($dob)->diff(\Carbon\Carbon::now())->format('%y');
        $postParams['age'] = $age;

        //Store the image
        if (isset($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/users'), $imageName);
            $user->image = $imageName;
        }

        // Update the passsword if and only if it is provided by the user
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        // Update all other attributes rather than image and password
        $user->update($postParams);

        return response()->json($user, 200);
        // print_r($id);exit;
        // echo"here"; exit();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // echo "here"; exit;
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'Profile deleted successfully']);
    }
}
