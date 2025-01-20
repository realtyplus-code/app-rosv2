<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\UserRepository;
use App\Interfaces\Role\RoleRepositoryInterface;
use App\Interfaces\User\UserRepositoryInterface;
use App\Repositories\Incident\IncidentRepository;
use App\Repositories\Property\PropertyRepository;
use App\Repositories\Provider\ProviderRepository;
use App\Repositories\Insurance\InsuranceRepository;
use App\Repositories\EnumOption\EnumOptionRepository;
use App\Interfaces\Incident\IncidentRepositoryInterface;
use App\Interfaces\Property\PropertyRepositoryInterface;
use App\Interfaces\Provider\ProviderRepositoryInterface;
use App\Repositories\UserProperty\UserPropertyRepository;
use App\Repositories\UserRelation\UserRelationRepository;
use App\Interfaces\Insurance\InsuranceRepositoryInterface;
use App\Interfaces\EnumOption\EnumOptionRepositoryInterface;
use App\Repositories\IncidentAction\IncidentActionRepository;
use App\Repositories\ProviderService\ProviderServiceRepository;
use App\Interfaces\UserProperty\UserPropertyRepositoryInterface;
use App\Interfaces\UserRelation\UserRelationRepositoryInterface;
use App\Repositories\IncidentProvider\IncidentProviderRepository;
use App\Interfaces\IncidentAction\IncidentActionRepositoryInterface;
use App\Interfaces\ProviderService\ProviderServiceRepositoryInterface;
use App\Interfaces\IncidentProvider\IncidentProviderRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProviderRepositoryInterface::class, ProviderRepository::class);
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
        $this->app->bind(UserPropertyRepositoryInterface::class, UserPropertyRepository::class);
        $this->app->bind(InsuranceRepositoryInterface::class, InsuranceRepository::class);
        $this->app->bind(IncidentRepositoryInterface::class, IncidentRepository::class);
        $this->app->bind(IncidentActionRepositoryInterface::class, IncidentActionRepository::class);
        $this->app->bind(EnumOptionRepositoryInterface::class, EnumOptionRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(ProviderServiceRepositoryInterface::class, ProviderServiceRepository::class);
        $this->app->bind(IncidentProviderRepositoryInterface::class, IncidentProviderRepository::class);
        $this->app->bind(UserRelationRepositoryInterface::class, UserRelationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191); 
    }
}
