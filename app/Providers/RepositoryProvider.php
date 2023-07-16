<?php

namespace App\Providers;

use App\Support\Discount\DiscountValidator;
use App\Support\Discount\Validator\Contracts\DiscountValidatorInterface;
use Carbon\Laravel\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    public function register() {

        $this->app->bind(
            DiscountValidatorInterface::class,
            DiscountValidator::class
        );
    }
    public function boot()
    {
        //
    }

}
