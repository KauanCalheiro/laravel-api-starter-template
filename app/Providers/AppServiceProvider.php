<?php

namespace App\Providers;

use App\Rules\PhoneNumber;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend(
            'phone_number',
            function ($attribute, $value, $parameters, $validator) {
                $failedMessage = null;

                $rule = new PhoneNumber();
                $rule->validate($attribute, $value, function ($message) use (&$failedMessage) {
                    $failedMessage = $message;
                });

                return $failedMessage === null;
            },
            __('validation.phone_number'),
        );

        Validator::replacer(
            'phone_number',
            function ($message, $attribute, $rule, $parameters) {
                $example = $parameters[0] ?? '(21) 91234‑5678';
                return str_replace(':format', $example, $message);
            },
        );
    }
}
