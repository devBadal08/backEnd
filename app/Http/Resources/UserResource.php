<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Get all fields of the user model
        // $userFields = $this->resource->toArray();

        // Merge additional fields
        $fields = [
            'token' => $this->createToken("Token")->plainTextToken,
            'roles' => $this->roles->pluck('name') ?? [],
            'roles_permissions' => $this->getPermissionsViaRoles()->pluck('name') ?? [],
            'permissions' => $this->permissions->pluck('name') ?? [],

            'image_url' => $this->image ? asset('images/users/' . $this->image) : null,
            'id' => $this->id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'max_profiles_limit' => $this->max_profiles_limit,
            'created_by' => $this->created_by,
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
        ];

        // Include profiles
        if ($this->profiles) {
            $fields['profiles'] = $this->profiles->toArray();
        }

        return $fields;


        // Merge all fields together
        // return array_merge($userFields, $additionalFields);
    }
}
