<?php

namespace App\Models;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;

    public $resource = CategoryResource::class;
    public $resourceCollection = CategoryCollection::class;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable  = [
    	'name',
    	'description'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function products()
    {
    	return $this->belongsToMany(Product::class);
    }

}
