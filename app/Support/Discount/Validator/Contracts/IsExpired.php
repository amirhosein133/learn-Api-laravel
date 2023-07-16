<?php

namespace App\Support\Discount\Validator\Contracts;

use App\Models\User;

class IsExpired extends AbstractDiscountVlidator
{
    public function validate(User $user)
    {
       dd($user);
    }
}
