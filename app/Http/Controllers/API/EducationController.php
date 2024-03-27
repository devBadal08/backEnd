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

    // Update education for a profile
    public function updateEducation(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'profile_id' => 'required',
            'education' => 'required|array', // Ensure education is an array
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle education update
        $profileId = $request->profile_id;
        $educations = $request->education;
            // print_r($educations); exit;


        foreach ($educations as $education) {
            $educationArr = json_decode($education);
            // print_r($education); exit;
            print_r($educationArr); exit;

            // $educationId = $educationArr->id; // Assuming you're passing education ID for update

            // Find the education record to update
            $profileEducation = ProfileEducation::where('id', $educationArr->id)
                ->where('profile_id', $profileId)
                ->first();
                
// print_r($profileEducation); exit;
            if ($profileEducation) {
                // Update education fields
                $postData = [
                    'type' => $educationArr->type,
                    'organization_name' => $educationArr->organization_name,
                    'degree' => $educationArr->degree,
                    'start_year' => $educationArr->start_year,
                    'end_year' => $educationArr->end_year,
                ];
                $matchArr = [
                    'profile_id'=> $profileId,
                    'id'=> $educationArr->id,
                    
                ];
                $profileEducation->updateOrCreate($postData, $matchArr);
            }
        }

        // Fetch updated education data for the profile
        $updatedProfileEducation = ProfileEducation::where('profile_id', $profileId)->get();

        return response()->json(['data' => $updatedProfileEducation]);
    }

    // Update education for a profile
// public function updateEducation(Request $request)
// {
//     // Validation
//     $validator = Validator::make($request->all(), [
//         'profile_id' => 'required',
//         'education' => 'required|array', // Ensure education is an array
//     ]);

//     if ($validator->fails()) {
//         return response()->json($validator->errors(), 422);
//     }

//     // Handle education update
//     $profileId = $request->profile_id;
//     $educations = $request->education;

//     foreach ($educations as $education) {
//         $educationArr = json_decode($education);

//         if (!isset($educationArr->id)) {
//             // If ID is not provided, skip this education item
//             continue;
//         }

//         $educationId = $educationArr->id;

//         // Find the education record to update
//         $profileEducation = ProfileEducation::where('id', $educationId)
//             ->where('profile_id', $profileId)
//             ->first();

//         if ($profileEducation) {
//             // Update education fields
//             $profileEducation->update([
//                 'type' => $educationArr->type,
//                 'organization_name' => $educationArr->organization_name,
//                 'degree' => $educationArr->degree,
//                 'start_year' => $educationArr->start_year,
//                 'end_year' => $educationArr->end_year,
//             ]);
//         }
//     }

//     // Fetch updated education data for the profile
//     $updatedProfileEducation = ProfileEducation::where('profile_id', $profileId)->get();

//     return response()->json(['data' => $updatedProfileEducation]);
// }




    //Update the edcuation of profile
    // public function updateEducation(Request $request, $id)
    // {
    //     // Validation for required fields
    //     $validator = Validator::make($request->all(), [
    //         'profile_id' => 'required',
    //         'education' => 'required|array',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }

    //     // Fetch the education to update based on ID
    //     $education = ProfileEducation::find($id);
    //     print_r($education); exit;

    //     // Check if education exists
    //     // if (!$education) {
    //     //     return response()->json(['error' => 'Education not found'], 404);
    //     // }

    //     // Handle education update
    //     // $educations = $request->input('education'); // Assuming it's a single education object
    //     // print_r($request->input('education')); exit;

    //     // $postData = [
    //     //     'profile_id' => 11,
    //     //     'type' => 'cc',
    //     //     'organization_name' => 'ghgg',
    //     //     'degree' => 'ghfhg',
    //     //     'start_year' => '1999',
    //     //     'end_year' => '2001'
    //     // ];



    //     // Return updated education data
    //     $profileEducation = ProfileEducation::with('profile')->find($id); // Eager load profile
    //     // $updatedEducation = ProfileEducation::with('profile')->find($id); // Eager load profile
    //     // print_r($updatedEducation); exit; 

    //     // return response()->json(['data' => $updatedEducation]);
    //     return response()->json(['data' => $profileEducation]);
    // }

    // //Update the edcuation of profile
    // public function updateEducation(Request $request, $profile_id)
    // {
    //     // Validation for required fields
    //     $validator = Validator::make($request->all(), [
    //         'profile_id' => 'required',
    //         'education' => 'required|array',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }

    //     // Update education records
    //     if ($request->has('education') && !empty($request->education)) {
    //         $educations = $request->education;
    //         foreach ($educations as $education) {
    //             ProfileEducation::where('profile_id', $profile_id)
    //                 ->where('id', $education['id']) // Assuming each education record has an 'id' field
    //                 ->update([
    //                     'profile_id' => $request->profile_id,
    //                     'type' => $education['type'],
    //                     'organization_name' => $education['organization_name'],
    //                     'degree' => $education['degree'],
    //                     'start_year' => $education['start_year'],
    //                     'end_year' => $education['end_year']

    //                 ]);
    //         }
    //     }

    //     // Retrieve and return updated education records
    //     $updatedEducations = ProfileEducation::where('profile_id', $profile_id)->get();
    //     return response()->json(['data' => $updatedEducations]);
    // }
}
