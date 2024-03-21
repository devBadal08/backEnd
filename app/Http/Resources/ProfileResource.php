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
        // return parent::toArray($request);
        // $additionalFields = [
        // //     'first_name'=> $request->first_name,
        // //     'first_name'=> $request->first_name,
        // //     'first_name'=> $request->first_name,
        // //     'first_name'=> $request->first_name,
        // //     'image'=> fullURL(public\images\profiles).'/'.$request->image,
        // 'image' => $this->image ? asset('storage/' . $this->image) : null,
        // ];

        // Get all fields of the profile model
        // $ProfileFields = $this->resource->toArray();
    
        // Merge additional fields
        $ProfileFields = [
            'image_url' => $this->image ? asset('images/profiles/' . $this->image) : null,
            'id' => $this->id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'dob' => $this->dob,
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
            'educations' => $this->educations,
        ];
    
        // Merge all fields together
        // return array_merge($ProfileFields, $additionalFields);
        return $ProfileFields;
    }
}
