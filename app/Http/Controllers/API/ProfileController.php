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
        $weight = $request->query('weight');
        $height = $request->query('height');
        $siblings = $request->query('siblings');
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
        if (isset($weight) && !empty($weight)) {
            $profilesQuery->filterByWeight($weight);
        }
        if (isset($height) && !empty($height)) {
            $profilesQuery->filterByHeight($height);
        }
        if (isset($siblings) && !empty($siblings)) {
            $profilesQuery->filterBySiblings($siblings);
        }


        return ProfileResource::collection($profilesQuery->paginate($perPage));
    }

    /**
     * Display the specified resource.
     * Display the individual profile
     */

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
        $customValidation = [
            'email' => 'required', //email validation
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:500', //image validation
        ];
       
        //vadlidation
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                $customValidation
            )
        );

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
        $profile->phone = $request->phone;
        $profile->alt_phone = $request->alt_phone;
        $profile->dob = $request->dob;
        $profile->age = \Carbon\Carbon::parse($request->dob)->age; // Calculate age from DOB and store it
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
        $profile->father_name = $request->father_name;
        $profile->father_occupation = $request->father_occupation;
        $profile->mother_name = $request->mother_name;
        $profile->mother_occupation = $request->mother_occupation;
        $profile->mothers_father_name = $request->mothers_father_name;
        $profile->mother_village = $request->mother_village;
        $profile->mother_city = $request->mother_city;
        $profile->sibling_comment = $request->sibling_comment;

        // If siblings information is provided, assign it to the profile
        if ($request->has('siblings')) {
            $profile->siblings = $request->siblings;
            $profile->number_of_brothers = $request->number_of_brothers;
            $profile->number_of_sisters = $request->number_of_sisters;
        } else {
            // If siblings information is not provided, set it to null
            $profile->siblings = null;
            $profile->number_of_brothers = null;
            $profile->number_of_sisters = null;
        }

        $profile->save();
        return new ProfileResource($profile);
    }

    // Update the profile by a manager
    public function profileUpdate(Request $request, $id)
    {
        $profile = auth()->user();
        $customValidation = [
            'email' => [
                'required',
                'email',
                Rule::unique('profiles')->ignore($id) //email validation
            ],
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500', //image validation
        ];
        //vadlidation
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                $customValidation
            )
        );

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors' => $validator->errors()
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
            'father_name'  => $request->father_name,
            'father_occupation'  => $request->father_occupation,
            'mother_name'  => $request->mother_name,
            'mother_occupation'  => $request->mother_occupation,
            'mothers_father_name'  => $request->mothers_father_name,
            'mother_village'  => $request->mother_village,
            'mother_city'  => $request->mother_city,
            'sibling_comment' => $request->sibling_comment,
        ];

        // Calculate the age from DOB and Store it 
        $dob = $request->dob;
        $age = \Carbon\Carbon::parse($dob)->diff(\Carbon\Carbon::now())->format('%y');
        $postParams['age'] = $age;

        if (isset($request->image) && !empty($request->image)) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/profiles'), $imageName);
            $postParams['image'] = $imageName;
        }

        // Check if siblings information is provided
        if (isset($request->siblings) && !empty($request->siblings)) {
            $postParams['siblings'] = $request->siblings;
            $postParams['number_of_brothers'] = $request->number_of_brothers;
            $postParams['number_of_sisters'] = $request->number_of_sisters;
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

    private function getValidationRules($isNew = true)
    {
        return [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required',
            'gender' => 'required',
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
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:500', //image validation
            'father_name' => 'required',
            'father_occupation' => 'required',
            'mother_name' => 'required',
            'mother_occupation' => 'required',
            'mothers_father_name' => 'required',
            'mother_village' => 'nullable',
            'mother_city' => 'required',
            'siblings' => 'sometimes|required|integer', // siblings is optional but if provided, number of brothers and sisters are required
            'number_of_brothers' => 'required_with:siblings|integer',
            'number_of_sisters' => 'required_with:siblings|integer',
            'sibling_comment' => 'nullable',
            // Add custom validation rule to ensure sum of brothers and sisters equals siblings
            'number_of_brothers' => [
                'required_with:siblings',
                function ($attribute, $value, $fail) use ($isNew) {
                    if ($isNew && request()->filled('siblings')) {
                        $siblings = request('siblings');
                        $totalBrothersAndSisters = request('number_of_brothers') + request('number_of_sisters');
                        if ($siblings != $totalBrothersAndSisters) {
                            $fail('The sum of number of brothers and number of sisters must equal the number of siblings.');
                        }
                    }
                },
            ],
            'number_of_sisters' => [
                'required_with:siblings',
                function ($attribute, $value, $fail) use ($isNew) {
                    if ($isNew && request()->filled('siblings')) {
                        $siblings = request('siblings');
                        $totalBrothersAndSisters = request('number_of_brothers') + request('number_of_sisters');
                        if ($siblings != $totalBrothersAndSisters) {
                            $fail('The sum of number of brothers and number of sisters must equal the number of siblings.');
                        }
                    }
                },
            ],
        ];
    }
}
