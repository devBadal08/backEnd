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
        $profileEducation = ProfileEducation::where('profile_id',$request->profile_id)->get();
        return response()->json(['data'=> $profileEducation]);

    }
}
