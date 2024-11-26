<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddTrickNotifiedByEmailSubscriber implements EventSubscriberInterface
{
    
    public function onKernelRequest(RequestEvent $event): void
    {

       
        if ($event->getRequest()->server->get('REMOTE_ADDR') != '172.18.0.1') {
            dd($event->getRequest()->server->get('REMOTE_ADDR'));            
            $event->setResponse(new Response('Access denied.'));
        }
     //   dd($event);
        // ...
    }

    public static function getSubscribedEvents(): array
    {
        return [
         KernelEvents::REQUEST => 'onKernelRequest',
          //  kernelEvents::RESPONSE => 'onkernelResponse'
        ];
    }
}
