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
        $userFields = $this->resource->toArray();
    
        // Merge additional fields
        $additionalFields = [
            'token' => $this->createToken("Token")->plainTextToken,
            'roles' => $this->roles->pluck('name') ?? [],
            'roles_permissions' => $this->getPermissionsViaRoles()->pluck('name') ?? [],
            'permissions' => $this->permissions->pluck('name') ?? []
        ];
    
        // Merge all fields together
        return array_merge($userFields, $additionalFields);
    }
}