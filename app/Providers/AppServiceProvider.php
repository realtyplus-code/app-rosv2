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
use App\Repositories\Attachment\AttachmentRepository;
use App\Repositories\EnumOption\EnumOptionRepository;
use App\Interfaces\Incident\IncidentRepositoryInterface;
use App\Interfaces\Property\PropertyRepositoryInterface;
use App\Interfaces\Provider\ProviderRepositoryInterface;
use App\Repositories\Contracts\PropertyHistoryRepository;
use App\Repositories\UserProperty\UserPropertyRepository;
use App\Repositories\UserRelation\UserRelationRepository;
use App\Interfaces\Insurance\InsuranceRepositoryInterface;
use App\Interfaces\Attachment\AttachmentRepositoryInterface;
use App\Interfaces\EnumOption\EnumOptionRepositoryInterface;
use App\Repositories\Contracts\UserPropertyHistoryRepository;
use App\Repositories\IncidentAction\IncidentActionRepository;
use App\Interfaces\Contract\PropertyHistoryRepositoryInterface;
use App\Repositories\CountryRelation\CountryRelationRepository;
use App\Repositories\ProviderService\ProviderServiceRepository;
use App\Interfaces\UserProperty\UserPropertyRepositoryInterface;
use App\Interfaces\UserRelation\UserRelationRepositoryInterface;
use App\Repositories\IncidentProvider\IncidentProviderRepository;
use App\Interfaces\Contract\UserPropertyHistoryRepositoryInterface;
use App\Repositories\InsuranceProperty\InsurancePropertyRepository;
use App\Interfaces\IncidentAction\IncidentActionRepositoryInterface;
use App\Interfaces\CountryRelation\CountryRelationRepositoryInterface;
use App\Interfaces\ProviderService\ProviderServiceRepositoryInterface;
use App\Interfaces\IncidentProvider\IncidentProviderRepositoryInterface;
use App\Interfaces\InsuranceProperty\InsurancePropertyRepositoryInterface;

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
        $this->app->bind(AttachmentRepositoryInterface::class, AttachmentRepository::class);
        $this->app->bind(InsurancePropertyRepositoryInterface::class, InsurancePropertyRepository::class);
        $this->app->bind(UserPropertyHistoryRepositoryInterface::class, UserPropertyHistoryRepository::class);
        $this->app->bind(PropertyHistoryRepositoryInterface::class, PropertyHistoryRepository::class);
        $this->app->bind(CountryRelationRepositoryInterface::class, CountryRelationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191); 
    }
}
