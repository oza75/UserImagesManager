<?php
/**
 * @author Aboubacar Ouattara <abouba181@gmail.com>
 * @license MIT
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