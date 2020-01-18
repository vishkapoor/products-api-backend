<?php

namespace App\Http\Controllers\Buyers;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerTransactionController extends BaseApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        return $this->showAll($buyer->transactions);
    }
}
