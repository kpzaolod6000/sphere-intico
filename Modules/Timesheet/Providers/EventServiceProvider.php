<?php

namespace Modules\Timesheet\Providers;

use App\Events\GivePermissionToRole;
use Modules\Timesheet\Listeners\GiveRoleToPermission;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    protected $listen = [
        GivePermissionToRole::class =>[
            GiveRoleToPermission::class
        ],
    ];
}
