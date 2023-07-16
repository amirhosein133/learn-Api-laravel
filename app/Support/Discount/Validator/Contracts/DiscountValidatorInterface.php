<?php

namespace App\Support\Discount\Validator\Contracts;

use App\Models\User;

interface DiscountValidatorInterface
{
    public function setNextValidator(DiscountValidatorInterface $validator); // che class bayd next call beshe

    public function validate(User $user);

    public function next(User $user);
}
