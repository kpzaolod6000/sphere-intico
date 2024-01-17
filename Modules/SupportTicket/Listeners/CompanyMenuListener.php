<?php

namespace Modules\SupportTicket\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'SupportTicket';
        $menu = $event->menu;
        $menu->add([
            'title' => 'Support Dashboard',
            'icon' => '',
            'name' => 'support-dashboard',
            'parent' => 'dashboard',
            'order' => 140,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'dashboard.support-tickets',
            'module' => $module,
            'permission' => 'supportticket dashboard manage'
        ]);
        $menu->add([
            'title' => __('Support Ticket'),
            'icon' => 'headphones',
            'name' => 'support ticket',
            'parent' => null,
            'order' => 700,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'supportticket manage'
        ]);
        $menu->add([
            'title' => __('Tickets'),
            'icon' => '',
            'name' => 'tickets',
            'parent' => 'support ticket',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'support-tickets.index',
            'module' => $module,
            'permission' => 'ticket manage'
        ]);
        $menu->add([
            'title' => __('Knowledge Base'),
            'icon' => '',
            'name' => 'knowledgebase',
            'parent' => 'support ticket',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'support-ticket-knowledge.index',
            'module' => $module,
            'permission' => 'knowledgebase manage'
        ]);
        $menu->add([
            'title' => __('FAQ'),
            'icon' => '',
            'name' => 'faq',
            'parent' => 'support ticket',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'support-ticket-faq.index',
            'module' => $module,
            'permission' => 'faq manage'
        ]);
        $menu->add([
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'system setup',
            'parent' => 'support ticket',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'ticket-category.index',
            'module' => $module,
            'permission' => 'supportticket setup manage'
        ]);
    }
}
