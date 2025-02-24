<?php

namespace App\Providers;

use App\Models\ClasseOption;
use App\Observers\ClasseOptionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\AuthenticationRepositoryInterface',
            'App\Repositories\AuthenticationRepository',
        );

        $this->app->bind(
            'App\Repositories\TenantRepositoryInterface',
            'App\Repositories\TenantRepository',
        );
        
        $this->app->bind(
            'App\Repositories\AdminRepositoryInterface',
            'App\Repositories\AdminRepository',
        );
        $this->app->bind(
            'App\Repositories\FiliereRepositoryInterface',
            'App\Repositories\FiliereRepository',
        );
        $this->app->bind(
            'App\Repositories\EleveTenantRepositoryInterface',
            'App\Repositories\EleveTenantRepository',
        );
        $this->app->bind(
            'App\Repositories\ProfesseurTenantRepositoryInterface',
            'App\Repositories\ProfesseurTenantRepository',
        );
        $this->app->bind(
            'App\Repositories\ClasseRepositoryInterface',
            'App\Repositories\ClasseRepository',
        );
        $this->app->bind(
            'App\Repositories\ClasseOptionRepositoryInterface',
            'App\Repositories\ClasseOptionRepository',
        );
        $this->app->bind(
            'App\Repositories\ProfesseurRepositoryInterface',
            'App\Repositories\ProfesseurRepository',
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ClasseOption::observe(ClasseOptionObserver::class);
    }
}
