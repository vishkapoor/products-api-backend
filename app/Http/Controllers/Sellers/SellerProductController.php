<?php

namespace App\Http\Controllers\Sellers;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Seller;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends BaseApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . ProductResource::class)
            ->only(['store', 'update']);

        $this->middleware('scope:manage-products')->except('index');
        $this->middleware('can:view,seller')->only(['index']);
        $this->middleware('can:sale,seller')->only(['store']);
        $this->middleware('can:edit-product,seller')->only(['update']);
        $this->middleware('can:delete-product,seller')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {

        if(request()->user()->tokenCan('read-general')
           || request()->user()->tokenCan('manage-products')) {

            $products = $seller->products;
            return $this->showAll($products);
        }

        throw new AuthorizationException('Invalid scope(s)');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Seller $seller)
    {

        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ]);

        $product = $seller->products()->create([
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'status' => Product::UNAVAILABLE_PRODUCT,
            'image' => $request->image->store(''),
        ]);

        return $this->showOne($product);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function edit(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $this->validate($request, [
            'quantity' => 'integer|min:1',
            'status' => 'in:'. Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT,
        ]);

        $this->checkSeller($seller, $product);
        if($request->has('name')) {
            $product->name = $request->name;
        }

        if($request->has('description')) {
            $product->description = $request->description;
        }

        if($request->has('quantity')) {
            $product->quantity = $request->quantity;
        }

        if($request->has('status')) {
            $product->status = $request->status;

            if($product->isAvailable() && $product->categories()->count() == 0) {
                return $this->errorResponse('An active product must have atleast one category', 409);
            }
        }

        if($request->hasFile('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }

        if($product->isClean()) {
            return $this->errorResponse('You need to specifiy a different value to update', 422);
        }

        $product->save();

        return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);

        Storage::delete($product->image);

        $product->delete();

        return $this->showOne($product);
    }

    protected function checkSeller(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id) {
            throw new HttpException(422, 'The specificed selller is not the actual seller of the product');
        }
    }


}
