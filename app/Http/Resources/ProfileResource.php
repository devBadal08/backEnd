<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        // Profile fields 
        $ProfileFields = [
            'image_url' => $this->image ? asset('images/profiles/' . $this->image) : null,
            'id' => $this->id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'dob' => $this->dob,
            'age' => $this->age,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'alt_phone' => $this->alt_phone,
            'username' => $this->username,
            'email' => $this->email,
            'village' => $this->village,
            'city' => $this->city,
            'state' => $this->state,
            'marital_status' => $this->marital_status,
            'height' => $this->height,
            'weight' => $this->weight,
            'hobbies' => $this->hobbies,
            'about_self' => $this->about_self,
            'about_job' => $this->about_job,
            'father_name' => $this->father_name,
            'father_occupation' => $this->father_occupation,
            'mother_name' => $this->mother_name,
            'mother_occupation' => $this->mother_occupation,
            'mothers_father_name' => $this->mothers_father_name,
            'mother_village' => $this->mother_village,
            'mother_city' => $this->mother_city,
            'siblings' => $this->siblings,
            'number_of_brothers' => $this->number_of_brothers,
            'number_of_sisters' => $this->number_of_sisters,
            'sibling_comment' => $this->sibling_comment,
            'educations' => $this->educations,
        ];

        return $ProfileFields;
    }
}
