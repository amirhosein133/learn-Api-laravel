<?php

namespace App\Support\Discount\Validator\Contracts;

use App\Models\User;

Abstract class AbstractDiscountVlidator implements DiscountValidatorInterface
{
    private  $nextValidator;
    public function setNextValidator(DiscountValidatorInterface $validator)
    {
       $this->nextValidator = $validator;
    }

    public function next(User $user)
    {
       if($this->nextValidator === null) {
           return true; // my condition is over
       }
       return $this->nextValidator->validate($user);
    }
}
