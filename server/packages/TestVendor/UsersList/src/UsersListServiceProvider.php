<?php

namespace TestVendor\UsersList;

use Illuminate\Support\ServiceProvider;

class UsersListServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'userslist');
    }

    public function register()
    {
        //
    }
}
