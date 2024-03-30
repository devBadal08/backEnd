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
        //  User fields
        $fields = [
            'token' => $this->createToken("Token")->plainTextToken,
            'id' => $this->id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'max_profiles_limit' => $this->max_profiles_limit,
            'created_by' => $this->created_by,
            'phone' => $this->phone,
            // 'alt_phone' => $this->alt_phone,
            'email' => $this->email,
            'image_url' => $this->image ? asset('images/users/' . $this->image) : null,
            'roles' => $this->roles->pluck('name') ?? [],
            'roles_permissions' => $this->getPermissionsViaRoles()->pluck('name') ?? [],
            'permissions' => $this->permissions->pluck('name') ?? [],
        ];

        // Include profiles
        if ($this->profiles) {
            $fields['profiles'] = $this->profiles->toArray();
        }

        return $fields;
    }
}
