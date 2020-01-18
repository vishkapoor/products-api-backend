<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::resource('buyers', 'Buyers\BuyersController', [
	'only' => ['index', 'show']
]);

Route::resource('buyers.transactions', 'Buyers\BuyerTransactionController', [
	'only' => ['index']
]);

Route::resource('buyers.products', 'Buyers\BuyerProductController', [
	'only' => ['index']
]);

Route::resource('buyers.sellers', 'Buyers\BuyerSellerController', [
	'only' => ['index']
]);

Route::resource('buyers.categories', 'Buyers\BuyerCategoryController', [
	'only' => ['index']
]);

Route::resource('sellers', 'Sellers\SellersController', [
	'only' => ['index', 'show']
]);

Route::resource('sellers.transactions', 'Sellers\SellerTransactionController', [
	'only' => ['index', 'show']
]);

Route::resource('sellers.categories', 'Sellers\SellerCategoryController', [
	'only' => ['index', 'show']
]);

Route::resource('sellers.buyers', 'Sellers\SellerBuyerController', [
	'only' => ['index', 'show']
]);

Route::resource('sellers.products', 'Sellers\SellerProductController');

Route::resource('categories', 'Categories\CategoriesController', [
	'except' => ['create', 'edit']
]);

Route::resource('categories.products', 'Categories\CategoryProductController', [
	'except' => ['create', 'edit']
]);

Route::resource('categories.sellers', 'Categories\CategorySellerController', [
	'except' => ['create', 'edit']
]);

Route::resource('categories.transactions', 'Categories\CategoryTransactionController', [
	'except' => ['create', 'edit']
]);

Route::resource('categories.buyers', 'Categories\CategoryBuyerController', [
	'except' => ['create', 'edit']
]);

Route::resource('products', 'Products\ProductsController', [
	'only' => ['index', 'show']
]);

Route::resource('products.transactions', 'Products\ProductTransactionController', [
	'only' => ['index', 'show']
]);

Route::resource('products.buyers', 'Products\ProductBuyerController', [
	'only' => ['index', 'show']
]);

Route::resource('products.buyers.transactions', 'Products\ProductBuyerTransactionController', [
	'only' => ['store']
]);

Route::resource('products.categories', 'Products\ProductCategoryController');

Route::resource('transactions', 'Transactions\TransactionsController', [
	'only' => ['index', 'show']
]);

Route::resource('transactions.categories', 'Transactions\TransactionCategoryController', [
	'only' => ['index']
]);

Route::resource('transactions.sellers', 'Transactions\TransactionSellerController', [
	'only' => ['index']
]);

Route::get('/user', function() {
    return response()->json(
    	new UserResource(request()->user())
    );
})->middleware('auth:api');

Route::resource('users', 'Users\UsersController', [
	'except' => ['create', 'edit']
]);

Route::get('/users/verify/{token}', [
	'uses' => 'Users\UsersController@verify',
	'as' => 'verify'
]);

Route::get('/users/{user}/resend', [
	'uses' => 'Users\UsersController@resend',
	'as' => 'resend'
]);

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');