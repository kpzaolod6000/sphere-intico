<?php

namespace Modules\SupportTicket\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'SupportTicket';
        $menu = $event->menu;
        $menu->add([
            'title' => 'Support Ticket',
            'name' => 'support-ticket',
            'order' => 320,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'supportticket_sidenav',
            'module' => $module,
            'permission' => 'supportticket setting'
        ]);

    }
}
