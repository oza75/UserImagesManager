<?php
/**
 * Created by PhpStorm.
 * User: aboubacar
 * Date: 18/04/18
 * Time: 19:28
 */

namespace Oza\UserImagesManager\Providers;


use Illuminate\Support\ServiceProvider;

class ManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '../config/profile.php' => config_path('profile.php'),
        ]);
    }

    public function register()
    {}
}