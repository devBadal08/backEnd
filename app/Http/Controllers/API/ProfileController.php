<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\ProfileEducation;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // To list the all profile under a particular manager
    public function index(Request $request, $id)
    {
        // $manager = User::findOrFail($id);
        // $profiles = (($manager->profiles)->paginate(1));

        $profiles = Profile::where('user_id', auth()->user()->id)->paginate(10);


        $perPage = $request->query('per_page', 10);
        $minAge = $request->query('min_age');
        $birthYear = $request->query('birth_year');
        $gender = $request->query('gender');
        $village = $request->query('village');
        $city = $request->query('city');
        $state = $request->query('state');

        if ($minAge || $birthYear || $gender || $village || $city || $state) {
            $profiles = User::filterByAge($minAge)
                ->filterByBirthYear($birthYear)
                ->filterByGender($gender)
                ->filterByLocation($village, $city, $state)
                ->paginate($perPage);
        }

        return response()->json(['profiles' => $profiles]);
    }
   

    // To store the profile under a particular manager
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
            'village' => 'required',
            'city' => 'required',
            'state' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'hobbies' => 'required',
            'about_self' => 'required',
            'about_job' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:500', //image validation
            // 'education' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user(); // Get currently logged-in user

        //Store the image
        if (isset($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/profiles'), $imageName);
            $user->image = $imageName;
        }
        // dd($request);

        // $profile->user_id = $user->id; // Associate profile with manager
        $profile->user_id = Auth::id();
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

        // Handle education creation 
        if (isset($request->education) && !empty($request->education)){
            $educations = $request->input('education', []);
            foreach ($educations as $education) {
                $educationArr = json_decode($education);
                // print_r($educationArr->degree);
                // die();
                $postData = [
                    'profile_id' => $profile->id,
                    'type' => $educationArr->type,
                    'organization_name' => $educationArr->organization_name,
                    'degree' => $educationArr->degree,
                    'start_year' => $educationArr->start_year,
                    'end_year' => $educationArr->end_year
                ];
                $profileEducation = ProfileEducation::create($postData);
            }
        }

        return response()->json($profile);
        // dd($profile);

    }

    // To update the profile by a manager

    public function profile_update(Request $request, $id)
    {
        $profile = auth()->user();

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
                Rule::unique('profiles')->ignore($id)
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
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:500', //image validation
            // 'education' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $profile = Profile::find($id);
        $postParams = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'password' => 'nullable|min:8',
            // 'c_password' => 'same:password',
            // 'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500',
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

        return response()->json($profile, 200);
    }
}
