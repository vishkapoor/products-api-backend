<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'identifier' => (int) $this->id,
            'name' => (string) $this->name,
            'email' => (string) $this->email,
            'isVerified' => (int) $this->verified,
            'isAdmin' => ($this->admin == 'true'),
            'creationDate' => (string) $this->created_at,
            'lastChanged' => (string) $this->updated_at,
            'deletedDate' => isset($this->deleted_at)
                ? (string) $this->deleted_at
                : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $this->id)
                ]
            ]
        ];
    }

    public static function originalAttribute($index)
    {

        $attributes = [
            'identifier' => 'id',
            'name' => 'name',
            'email' => 'email',
            'password' => 'password',
            'isVerified' => 'verified',
            'isAdmin' => 'admin',
            'creationDate' => 'created_at',
            'lastChanged' => 'updated_at',
            'deletedDate' => 'deleted_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }

    public static function transformedAttribute($index)
    {

        $attributes = [
            'id' => 'identifier',
            'name' => 'name',
            'password' => 'password',
            'email' => 'email',
            'verified' => 'isVerified',
            'admin' => 'isAdmin',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChanged',
            'deleted_at' => 'deletedDate'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }


}
