<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //

        // $permissions = \App\Permission::with('roles')->get();
        // foreach ($permissions as $permission) {
        //     Gate::define($permission->name, function($user) use ($permission) {
        //         return $user->hasPermission($permission);
        //     });
        // }
    }
}
