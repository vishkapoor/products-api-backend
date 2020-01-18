<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'details' => (string) $this->description,
            'stock' => (string) $this->quantity,
            'situation' => (string) $this->status,
            'picture' => url("img/" . $this->image),
            'seller' => (int) $this->seller_id,
            'creationDate' => (string) $this->created_at,
            'lastChanged' => (string) $this->updated_at,
            'deletedDate' => isset($this->deleted_at)
                ? (string) $this->deleted_at
                : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $this->id)
                ],
                [
                    'rel' => 'product.buyers',
                    'href' => route('products.buyers.index', $this->id),
                ],
                [
                    'rel' => 'product.categories',
                    'href' => route('products.categories.index', $this->id),
                ],
                [
                    'rel' => 'product.transactions',
                    'href' => route('products.transactions.index', $this->id),
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show', $this->seller_id),
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
            'stock' => 'quantity',
            'situation' => 'status',
            'picture' => 'image',
            'seller' => 'seller_id',
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
             'quantity' => 'stock',
             'status' => 'situation',
             'image' => 'picture',
             'seller_id' => 'seller',
             'created_at' => 'creationDate',
             'updated_at' => 'lastChanged',
             'deleted_at' => 'deletedDate'
        ];


        return isset($attributes[$index]) ? $attributes[$index] : null;

    }



}
