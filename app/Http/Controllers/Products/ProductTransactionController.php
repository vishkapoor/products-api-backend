<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductTransactionController extends BaseApiController
{

    public function __construct()
    {
        parent::__construct();
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $this->allowedAdminAction();

        $transactions = $product->transactions;

        return $this->showAll($transactions);
    }

}
