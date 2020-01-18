<?php

namespace App\Http\Controllers\Buyers;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class BuyersController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('scope:read-general')->only('index');

        $this->middleware('can:view,buyer')->only(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowedAdminAction();
        $buyers = Buyer::all();
        return $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        return $this->showOne($buyer);
    }

}
