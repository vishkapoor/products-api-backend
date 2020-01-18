<?php

namespace App\Models;

use App\Http\Resources\SellerCollection;
use App\Http\Resources\SellerResource;
use App\Models\Product;
use App\Scopes\SellerScope;
use App\User;

class Seller extends User
{

    public $resource = SellerResource::class;
    public $resourceCollection = SellerCollection::class;

	public static function boot()
	{
		parent::boot();

		// self::addGlobalScope(new SellerScope);
	}

    public function products()
    {
    	return $this->hasMany(Product::class);
    }
}
