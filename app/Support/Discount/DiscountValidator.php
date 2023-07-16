<?php

namespace App\Support\Discount;

use App\Models\User;
use App\Support\Discount\Validator\Contracts\IsExpired;

class DiscountValidator
{
    public function validat(User $user)
    {
        $isExpired = new IsExpired();

        return $isExpired->validate($user);
    }

}
