<?php

namespace App\Policies;

use App\Traits\AdminPolicyActions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    use AdminPolicyActions;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $authenticatedUser, User $model)
    {
        return $authenticatedUser->id === $model->id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $authenticatedUser, User $model)
    {
        return $authenticatedUser->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $authenticatedUser, User $model)
    {
        return $authenticatedUser->id === $model->id &&
           $authenticatedUser->token()->client->personal_access_client;
    }
}
