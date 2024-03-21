<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\ProfileEducation;
use Illuminate\Support\Facades\Validator;



class EducationController extends Controller
{
    // education store of profile
    public function storeEducation(Request $request)
    {

        //validation
        $validator = Validator::make($request->all(), [
            'profile_id' => 'required',
            'education' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle education creation 
        if (isset($request->education) && !empty($request->education)) {
            $educations = $request->input('education', []);
            foreach ($educations as $education) {
                $educationArr = json_decode($education);
                // print_r($educationArr->degree);
                // die();
                $postData = [
                    'profile_id' => $request->profile_id,
                    'type' => $educationArr->type,
                    'organization_name' => $educationArr->organization_name,
                    'degree' => $educationArr->degree,
                    'start_year' => $educationArr->start_year,
                    'end_year' => $educationArr->end_year
                ];

                $profileEducation = ProfileEducation::create($postData);
            }
        }
        $profileEducation = ProfileEducation::where('profile_id', $request->profile_id)->get();
        return response()->json(['data' => $profileEducation]);
    }

    //Update the edcuation of profile
    public function updateEducation(Request $request, $id)
    {
        // Validation for required fields
        $validator = Validator::make($request->all(), [
            'profile_id' => 'required',
            'education' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Fetch the education to update based on ID
        $education = ProfileEducation::find($id);
        print_r($education); exit;

        // Check if education exists
        // if (!$education) {
        //     return response()->json(['error' => 'Education not found'], 404);
        // }

        // Handle education update
        // $educations = $request->input('education'); // Assuming it's a single education object
        // print_r($request->input('education')); exit;
        
        // $postData = [
        //     'profile_id' => 11,
        //     'type' => 'cc',
        //     'organization_name' => 'ghgg',
        //     'degree' => 'ghfhg',
        //     'start_year' => '1999',
        //     'end_year' => '2001'
        // ];

        
        // Handle education creation 
        if (isset($request->education) && !empty($request->education)) {
            $educations = $request->input('education', []);
            foreach ($educations as $education) {
                $educationArr = json_decode($education);
                // print_r($educationArr->degree);
                // die();
                $postData = [
                    'profile_id' => $request->profile_id,
                    'type' => $educationArr->type,
                    'organization_name' => $educationArr->organization_name,
                    'degree' => $educationArr->degree,
                    'start_year' => $educationArr->start_year,
                    'end_year' => $educationArr->end_year
                ];

                $profileEducation = ProfileEducation::updated($postData);
            }
        }
        // Return updated education data
        $profileEducation = ProfileEducation::with('profile')->find($id); // Eager load profile
        // $updatedEducation = ProfileEducation::with('profile')->find($id); // Eager load profile
        // print_r($updatedEducation); exit; 

        // return response()->json(['data' => $updatedEducation]);
        return response()->json(['data' => $profileEducation]);
    }
}
