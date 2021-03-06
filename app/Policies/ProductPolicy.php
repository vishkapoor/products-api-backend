<?php

namespace App\Policies;

use App\Models\Product;
use App\Traits\AdminPolicyActions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;
    use AdminPolicyActions;


    /**
     * Determine whether the user can view the product.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function addCategory(User $user, Product $product)
    {
        return $user->id === $product->seller->id;
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function deleteCategory(User $user, Product $product)
    {
        return $user->id === $product->seller->id;
    }
}
