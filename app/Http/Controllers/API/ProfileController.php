<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // To list the all profile under a particular manager with educationk
    public function index(Request $request, $id)
    {
        $manager = User::findOrFail($id);
        $perPage = $request->query('per_page', 10);
        $minAge = $request->query('min_age');
        $birthYear = $request->query('birth_year');
        $gender = $request->query('gender');
        $village = $request->query('village');
        $city = $request->query('city');
        $state = $request->query('state');
        $searchTerm = $request->query('keyword');

        $profilesQuery = Profile::where('user_id', $manager->id)
            ->with('educations');

        if (isset($searchTerm) && !empty($searchTerm)) {
            $profilesQuery->filterBySearch($searchTerm);
        }
        if (isset($minAge) && !empty($minAge)) {
            $profilesQuery->filterByAge($minAge);
        }
        if (isset($birthYear) && !empty($birthYear)) {
            $profilesQuery->filterByBirthYear($birthYear);
        }
        if (isset($gender) && !empty($gender)) {
            $profilesQuery->filterByGender($gender);
        }
        if (isset($village) && !empty($village)) {
            $profilesQuery->filterByLocation($village, $city, $state);
        }
        if (isset($city) && !empty($city)) {
            $profilesQuery->filterByLocation($village, $city, $state);
        }
        if (isset($state) && !empty($state)) {
            $profilesQuery->filterByLocation($village, $city, $state);
        }

        return ProfileResource::collection($profilesQuery->paginate($perPage));
    }

    /**
     * Display the specified resource.
     */

    //Display the individual profile
    public function show(Request $request, $id)
    {
        // Check if the profile with the provided ID exists
        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        return new ProfileResource($profile);
    }


    // To create the profile with personal information under a particular manager
    public function store(Request $request)
    {
        $profile = new Profile;

        //validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password', // Add password validation
            'phone' => 'required|numeric|digits:10',
            'alt_phone' => 'nullable|numeric|digits:10',
            'username' => 'required',
            'marital_status' => 'required',
            'village' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'hobbies' => 'required',
            'about_self' => 'required',
            'about_job' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:500', //image validation
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Assuming the authenticated user is the manager creating the profile
        $user = $request->user();

        // Check if the user has reached their maximum profile limit
        $profilesCount = $user->profiles()->count();
        if ($profilesCount >= $user->max_profiles_limit) {
            return response()->json(['error' => 'Maximum limit reached for adding profiles under this manager.'], 403);
        }

        // Calculate the age from DOB and Store it 
        $dob = $request->dob;
        $age = \Carbon\Carbon::parse($dob)->diff(\Carbon\Carbon::now())->format('%y');
        $postParams['age'] = $age;

        //Store the image
        if (isset($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/profiles'), $imageName);
            $profile->image = $imageName;
        }

        $profile->user_id = Auth::id(); // Associate profile with manager
        $profile->first_name = $request->first_name;
        $profile->middle_name = $request->middle_name;
        $profile->last_name = $request->last_name;
        $profile->email = $request->email;
        $profile->password = bcrypt($request->password);
        $profile->phone = $request->phone;
        $profile->alt_phone = $request->alt_phone;
        $profile->dob = $request->dob;
        $profile->gender = $request->gender;
        $profile->username = $request->username;
        $profile->marital_status = $request->marital_status;
        $profile->village = $request->village;
        $profile->city = $request->city;
        $profile->state = $request->state;
        $profile->height = $request->height;
        $profile->weight = $request->weight;
        $profile->hobbies = $request->hobbies;
        $profile->about_self = $request->about_self;
        $profile->about_job = $request->about_job;
        // Set other profile fields from request
        $profile->save();
        return new ProfileResource($profile);
    }

    // Update the profile by a manager
    public function profileUpdate(Request $request, $id)
    {
        $profile = auth()->user();

        //vadlidation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required',
            'gender' => 'required',
            'phone' => 'required|numeric|digits:10',
            'alt_phone' => 'nullable|numeric|digits:10',
            'email' => [
                'required',
                'email',
                Rule::unique('profiles')->ignore($id) //email validation
            ],
            'password' => 'nullable|confirmed|min:8', // Password is optional, but if provided, needs confirmation and minimum length
            'password_confirmation' => 'nullable|required_with:password', // Confirmation required only if password is provided
            'username' => 'required',
            'marital_status' => 'required',
            'village' => 'required',
            'city' => 'required',
            'state' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'hobbies' => 'required',
            'about_self' => 'required',
            'about_job' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500', //image validation
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        // Check if the profile with the provided ID exists
        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        $postParams = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dob'  => $request->dob,
            'gender'  => $request->gender,
            'phone'  => $request->phone,
            'alt_phone'  => $request->alt_phone,
            'username'  => $request->username,
            'marital_status'  => $request->marital_status,
            'village'  => $request->village,
            'city'  => $request->city,
            'state'  => $request->state,
            'height'  => $request->height,
            'weight'  => $request->weight,
            'hobbies'  => $request->hobbies,
            'about_self'  => $request->about_self,
            'about_job'  => $request->about_job,
            'image'  => $request->image,
            'education'  => $request->education,
        ];

        if (isset($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/profiles'), $imageName);
            $profile->image = $imageName;
        }

        if ($request->has('password')) {
            $profile->password = bcrypt($request->password);
        }

        $profile->update($postParams);

        return new ProfileResource($profile);
    }

    //To Delete a profile with it's education
    public function destroy($id)
    {
        // Check if the profile with the provided ID exists
        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        // Check if profile belongs to the currently authenticated user 
        if (auth()->user()->id !== $profile->user_id) {
            return response()->json(['error' => 'Unauthorized deletion'], 403);
        }
        // Delete education data associated with the profile (assuming foreign key)
        $profile->educations()->delete();

        // Delete the profile itself
        $profile->delete();
        return response()->json(['message' => 'Profile deleted successfully']);
    }
}
