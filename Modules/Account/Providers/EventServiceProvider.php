<?php

namespace Modules\Account\Providers;

use App\Events\DefaultData;
use App\Events\GivePermissionToRole;
use Modules\Account\Listeners\DataDefault;
use App\Events\DeleteProductService;
use Modules\Account\Listeners\ProductCreate;
use Modules\Account\Listeners\ProductServiceDelete;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Account\Listeners\GiveRoleToPermission;
use Modules\Account\Listeners\ProductUpdate;
use Modules\ProductService\Events\CreateProduct;
use Modules\ProductService\Events\UpdateProduct;


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
        CreateProduct::class=>[
            ProductCreate::class
        ],
        UpdateProduct::class=>[
            ProductUpdate::class
        ],
    ];

}


