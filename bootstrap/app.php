<?php

require_once __DIR__ . '/../vendor/autoload.php';

# Load ENV Environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

# Create Lumen Application
$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

# Enable Laravel Facades (DB::)
$app->withFacades();

# Enable Laravel Eloquent Models
$app->withEloquent();

# Configure Mail Provider
$app->configure('mail');

# Configure Auth Provider
$app->configure('auth');

# Configure Chocolatey Provider
$app->configure('chocolatey');

# Configure Maintenance Provider
$app->configure('maintenance');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
*/

# Add Auth Middleware
$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
    'cors' => App\Http\Middleware\Cors::class,
    'maintenance' => App\Http\Middleware\Maintenance::class,
    'admin' => App\Http\Middleware\RequireAdmin::class,
    'cache' => App\Http\Middleware\CacheControl::class,
    'optimize' => App\Http\Middleware\PerformanceOptimizer::class,
]);

# Global middleware for performance and eco-design
$app->middleware([
    App\Http\Middleware\CacheControl::class,
    App\Http\Middleware\PerformanceOptimizer::class,
    App\Http\Middleware\EcoDesign::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
*/

$app->register(App\Providers\AppServiceProvider::class);

$app->register(App\Providers\SessionServiceProvider::class);

$app->register(App\Providers\ViewServiceProvider::class);

$app->register(App\Providers\AuthServiceProvider::class);

# Sofa\Eloquence removed - replaced with native Laravel functionality

$app->register(Intervention\Image\Laravel\ServiceProvider::class);

$app->register(App\Providers\NuxServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
*/

# Enable Lumen Controllers & Routes
$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__ . '/../routes/web.php';
});

return $app;
