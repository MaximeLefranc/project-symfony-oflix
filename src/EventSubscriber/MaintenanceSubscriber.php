<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;


class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $soonInMaintenance;

    public function __construct($soonInMaintenance)
    {
        $this->soonInMaintenance = $soonInMaintenance;
    }
    public function onKernelResponse(ResponseEvent $event): void
    {
        if ($this->soonInMaintenance) {
            $content = $event->getResponse()->getContent();
            $event->getResponse()->setContent(str_replace('<body>', '<body><div class="alert alert-danger">Maintenance prévue mardi 10 janvier à 17h00</div>', $content));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
