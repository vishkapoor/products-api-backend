<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends BaseApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . TransactionResource::class)
            ->only(['store']);

        $this->middleware('scope:purchase-product')->only(['store']);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $this->validate($request, [
            'quantity' => 'required|min:1'
        ]);

        if($buyer->id == $product->seller_id) {
            return $this->errorResponse('The buyer must be different from seller', 409);
        }

        if(!$buyer->isVerified()) {
            return $this->errorResponse('Buyer must be a verified user', 409);
        }

        if(!$product->seller->isVerified()) {
            return $this->errorResponse('Seller must be a verified user', 409);
        }

        if(!$product->isAvailable()) {
            return $this->errorResponse('The product is not available', 409);
        }

        if($product->quantity < $request->quantity) {
            return $this->errorResponse('The product does not have enough units for this transaction', 409);
        }

        try {

            DB::beginTransaction();

                $product->quantity -= $request->quantity;
                $product->save();

                $transaction = Transaction::create([
                    'quantity' => $request->quantity,
                    'buyer_id' => $buyer->id,
                    'product_id' => $product->id,
                ]);

            DB::commit();

            return $this->showOne($transaction, 201);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    }
}
