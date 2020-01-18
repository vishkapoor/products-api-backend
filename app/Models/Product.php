<?php

namespace App\Models;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Seller;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

	const AVAILABLE_PRODUCT = 'available';
	const UNAVAILABLE_PRODUCT = 'unavailable';

    public $resource = ProductResource::class;
    public $resourceCollection = ProductCollection::class;

    protected $dates = [
        'deleted_at'
    ];

    protected $hidden = [
        'pivot'
    ];

    protected $fillable  = [
    	'name',
    	'description',
    	'quantity',
    	'status',
    	'image',
    	'seller_id'
    ];

    public function isAvailable()
    {
    	return $this->status == self::AVAILABLE_PRODUCT;
    }

    public function categories()
    {
    	return $this->belongsToMany(Category::class);
    }

    public function transactions()
    {
    	return $this->hasMany(Transaction::class);
    }

    public function seller()
    {
    	return $this->belongsTo(Seller::class);
    }

}
