<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 👇 importa tu interfaz y la implementación
use App\Repositories\TaskRepositoryInterface;
use App\Repositories\FileTaskRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 👇 aquí va el binding (inyección de dependencias)
        $this->app->bind(TaskRepositoryInterface::class, FileTaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
