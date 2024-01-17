<?php

namespace Modules\Workflow\Providers;

use App\Events\CreateInvoice;
use App\Events\CreateProposal;
use App\Events\CreateUser;
use Modules\Lead\Events\CreateDeal;
use Modules\Workflow\Listeners\CreateInvoiceLis;
use Modules\Workflow\Listeners\CreateUserLis;
use Modules\Workflow\Listeners\CreateProposalLis;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Account\Events\CreateCustomer;
use Modules\Appointment\Events\CreateAppointment;
use Modules\CMMS\Events\CreateSupplier;
use Modules\Contract\Entities\ContractType;
use Modules\Contract\Events\CreateContract;
use Modules\Hrm\Events\CreateAward;
use Modules\Lead\Events\CreateLead;
use Modules\Pos\Events\CreatePurchase;
use Modules\Retainer\Events\CreateRetainer;
use Modules\SupportTicket\Events\CreateTicket;
use Modules\Taskly\Events\CreateProject;
use Modules\Training\Events\CreateTraining;
use Modules\Webhook\Listeners\CreateTrainingLis as ListenersCreateTrainingLis;
use Modules\Workflow\Listeners\CreateAppointmentLis;
use Modules\Workflow\Listeners\CreateAwardLis;
use Modules\Workflow\Listeners\CreateContractLis;
use Modules\Workflow\Listeners\CreateContractTypeLis;
use Modules\Workflow\Listeners\CreateCustomerLis;
use Modules\Workflow\Listeners\CreateDealLis;
use Modules\Workflow\Listeners\CreateLeadLis;
use Modules\Workflow\Listeners\CreateProjectLis;
use Modules\Workflow\Listeners\CreatePurchaseLis;
use Modules\Workflow\Listeners\CreateRetainerLis;
use Modules\Workflow\Listeners\CreateSupplierLis;
use Modules\Workflow\Listeners\CreateTicketLis;
use Modules\Workflow\Listeners\CreateTrainingLis;

// use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    protected $listen = [
        CreateInvoice::class =>[
            CreateInvoiceLis::class
        ],
        CreateUser::class =>[
            CreateUserLis::class
        ],
        CreateProposal::class =>[
            CreateProposalLis::class
        ],
        CreateRetainer::class =>[
            CreateRetainerLis::class
        ],
        CreateLead::class =>[
            CreateLeadLis::class
        ],
        CreateProject::class=>[
            CreateProjectLis::class
        ],
        CreateCustomer::class=>[
            CreateCustomerLis::class
        ],
        CreateTicket::class=>[
            CreateTicketLis::class
        ],
        CreateDeal::class=>[
            CreateDealLis::class
        ],
        CreateAppointment::class=>[
            CreateAppointmentLis::class
        ],
        CreatePurchase::class=>[
            CreatePurchaseLis::class
        ],
        CreateAward::class=>[
            CreateAwardLis::class
        ],
        CreateTraining::class=>[
            CreateTrainingLis::class
        ],
        CreateSupplier::class=>[
            CreateSupplierLis::class
        ],
        CreateContract::class=>[
            CreateContractLis::class
        ],
    ];
    
}
