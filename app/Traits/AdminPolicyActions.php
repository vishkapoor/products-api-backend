<?php

namespace App\Traits;

trait AdminPolicyActions
{
   public function before($user, $ability)
    {
        if($user->isAdmin()) {
            return true;
        }
    }
}