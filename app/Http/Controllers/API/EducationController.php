<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\ProfileEducation;
use Illuminate\Support\Facades\Validator;



class EducationController extends Controller
{
    // store education of profile
    public function storeEducation(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'profile_id' => 'required|exists:profiles,id',
            'education' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle education creation 
        if (isset($request->education) && !empty($request->education)) {
            $educations = $request->input('education', []);
            foreach ($educations as $education) {
                // $educationArr = json_decode($education);
                $postData = [
                    'profile_id' => $request['profile_id'],
                    'type' => $education['type'],
                    'organization_name' => $education['organization_name'],
                    'degree' => $education['degree'],
                    'start_year' => $education['start_year'],
                    'end_year' => $education['end_year']
                ];
                $profileEducation = ProfileEducation::create($postData);
            }
        }
        $profileEducation = ProfileEducation::where('profile_id', $request->profile_id)->get();
        return response()->json(['data' => $profileEducation]);
    }

    //update education for a profile
    public function updateEducation(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'profile_id' => 'required|exists:profiles,id',
            'education' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle education update
        $profileId = $request->profile_id;
        $educations = $request->education;

        foreach ($educations as $education) {
            $educationId = isset($education['id']) ? $education['id'] : ''; // Access 'id' as an array element

            // Update education fields
            $postData = [
                'type' => $education['type'],
                'organization_name' => $education['organization_name'],
                'degree' => $education['degree'],
                'start_year' => $education['start_year'],
                'end_year' => $education['end_year'],
            ];

            // Update or create education record
            $matchArr = [
                'profile_id' => $profileId,
                'id' => $educationId,
            ];
            ProfileEducation::updateOrCreate($matchArr, $postData);
        }

        // Fetch updated education data for the profile
        $updatedProfileEducation = ProfileEducation::where('profile_id', $profileId)->get();

        return response()->json(['data' => $updatedProfileEducation]);
    }
}
