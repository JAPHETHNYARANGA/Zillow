<?php

namespace App\Providers;

use App\Models\Property;
use App\Models\User;
use App\Policies\PropertyPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Property::class => PropertyPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
        // Remove Passport::routes(); - no Sanctum equivalent needed here
    }
}