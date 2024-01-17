<?php

namespace Modules\Sales\Providers;

use App\Events\DeleteProductService;
use Modules\Sales\Listeners\ProductServiceDelete;
use App\Events\DefaultData;
use App\Events\GivePermissionToRole;
use Modules\Sales\Listeners\GiveRoleToPermission;
use Modules\Sales\Listeners\DataDefault;
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
        DefaultData::class =>[
            DataDefault::class                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
        ],
        DeleteProductService::class =>[
            ProductServiceDelete::class
        ],
    ];

}
