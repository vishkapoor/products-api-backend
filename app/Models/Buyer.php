<?php

namespace App\Models;

use App\Http\Resources\BuyerCollection;
use App\Http\Resources\BuyerResource;
use App\Models\Transaction;
use App\Scopes\BuyerScope;
use App\User;

class Buyer extends User
{

    public $resource = BuyerResource::class;
    public $resourceCollection = BuyerCollection::class;

	public static function boot()
	{
		parent::boot();

		static::addGlobalScope(new BuyerScope);
	}

    public function transactions()
    {
    	return $this->hasMany(Transaction::class);
    }
}
