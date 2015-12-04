<?php

namespace Srmklive\Authy;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AuthyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__. '/../config/config.php' => config_path('authy.php'),
        ]);

        $this->publishes([
            __DIR__. '/../migrations/migration.php' => database_path('/migrations/' .
                str_replace(':', '', str_replace('-', '_', Carbon::now()->format("Y-m-d_H:i:s"))) . '_update_users_table.php'),
        ]);
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAuthy();
        $this->mergeConfig();
    }
    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerAuthy()
    {
        $this->app->singleton('authy', function () {
            return new Authy();
        });
    }
    /**
     * Merges user's and paypal's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__. '/../config/config.php', 'authy'
        );
    }
}
