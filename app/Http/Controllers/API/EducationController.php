<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\ProfileEducation;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    // Validation rules
    protected $rules = [
        'profile_id' => 'required|exists:profiles,id',
        'education' => 'required|array',
        'education.*.type' => 'required|string',
        'education.*.organization_name' => 'required|string',
        'education.*.degree' => 'required|string',
        'education.*.start_year' => 'required|date_format:Y-m',
        'education.*.end_year' => 'required|date_format:Y-m|after:education.*.start_year',
    ];

    // Custom error messages
    protected $messages = [
        'education.*.type.required' => 'Type is required for education.',
        'education.*.organization_name.required' => 'Organization name is required for education.',
        'education.*.degree.required' => 'Degree is required for education.',
        'education.*.start_year.required' => 'Start year is required for education.',
        'education.*.start_year.date_format' => 'Start year must be in the format YYYY-MM.',
        'education.*.end_year.required' => 'End year is required for education.',
        'education.*.end_year.date_format' => 'End year must be in the format YYYY-MM.',
        'education.*.end_year.after' => 'End year must be after the start year.',
    ];
    // store education of profile
    public function storeEducation(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        // Check for validation failure
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle education creation 
        if ($request->has('education') && !empty($request->education)) {
            foreach ($request->education as $education) {
                $postData = [
                    'profile_id' => $request->profile_id,
                    'type' => $education['type'],
                    'organization_name' => $education['organization_name'],
                    'degree' => $education['degree'],
                    'start_year' => $education['start_year'],
                    'end_year' => $education['end_year']
                ];
                $profileEducation = ProfileEducation::create($postData);
            }
        }

        // Fetch and return education data
        $profileEducation = ProfileEducation::where('profile_id', $request->profile_id)->get();
        return response()->json(['data' => $profileEducation]);
    }


    //update education for a profile
    public function updateEducation(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        // Check for validation failure
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
