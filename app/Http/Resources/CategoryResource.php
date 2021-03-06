<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'title' => (string) $this->name,
            'details' =>(string) $this->description,
            'creationDate' => (string)  $this->created_at,
            'lastChanged' => (string)  $this->updated_at,
            'deletedDate' => isset($this->deleted_at)
                ? (string) $this->deleted_at
                : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $this->id)
                ],
                [
                    'rel' => 'category.buyers',
                    'href' => route('categories.buyers.index', $this->id),
                ],
                [
                    'rel' => 'category.product',
                    'href' => route('categories.products.index', $this->id),
                ],
                [
                    'rel' => 'category.sellers',
                    'href' => route('categories.sellers.index', $this->id),
                ],
                [
                    'rel' => 'category.transactions',
                    'href' => route('categories.transactions.index', $this->id),
                ],
            ]
        ];
    }



    public static function originalAttribute($index)
    {

        $attributes = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
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
            'name' => 'title',
            'description' => 'details',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChanged',
            'deleted_at' => 'deletedDate'
        ];



        return isset($attributes[$index]) ? $attributes[$index] : null;

    }

}
