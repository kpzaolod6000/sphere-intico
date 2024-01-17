<?php

namespace Modules\SupportTicket\Listeners;
use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingEvent $event): void
    {
        $module = 'SupportTicket';

        if(in_array($module,$event->html->modules))
        {
            $methodName = 'index';
            $controllerClass = "Modules\\SupportTicket\\Http\\Controllers\\Company\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 320,
                        'module' => $module,
                        'permission' => 'supportticket setting'
                    ]);
                }
            }
        }
    }
}
