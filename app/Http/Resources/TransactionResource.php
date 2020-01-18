<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'quantity' => (string) $this->quantity,
            'buyer' => (int) $this->buyer_id,
            'product' => (int) $this->product_id,
            'creationDate' => (string) $this->created_at,
            'lastChanged' => (string) $this->updated_at,
            'deletedDate' => isset($this->deleted_at)
                ? (string) $this->deleted_at
                : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $this->id)
                ],
                [
                    'rel' => 'transaction.category',
                    'href' => route('transactions.categories.index', $this->id),
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show', $this->buyer_id),
                ],
                [
                    'rel' => 'transaction.seller',
                    'href' => route('transactions.sellers.index', $this->id),
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show', $this->product_id),
                ],
            ]
        ];
    }


    public static function originalAttribute($index)
    {

        $attributes = [
            'identifier' => 'id',
            'quantity' => 'quantity',
            'buyer' => 'buyer_id',
            'product' => 'product_id',
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
             'quantity' => 'quantity',
             'buyer_id' => 'buyer',
             'product_id' => 'product',
             'created_at' => 'creationDate',
             'updated_at' => 'lastChanged',
             'deleted_at' => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }



}
