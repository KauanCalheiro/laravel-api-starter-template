<?php

namespace App\Providers;

use App\Rules\PhoneNumber;
use Carbon\CarbonInterval;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        JsonResource::withoutWrapping();

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

        Passport::tokensExpireIn(CarbonInterval::days(15));
        Passport::refreshTokensExpireIn(CarbonInterval::days(30));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));
        Passport::enablePasswordGrant();
    }
}
