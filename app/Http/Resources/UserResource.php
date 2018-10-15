<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles->map(function (Role $role) {
                return [ 'name' => $role->name ];
            }),
            'path' => route('api.user.show', $this),
        ];
    }
}
