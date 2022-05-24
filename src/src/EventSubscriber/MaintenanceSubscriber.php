<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    /**
     * @var bool $alertMessage Will display flash message if true
     */
    private $alertMessage;

    /**
     * @var string $alertMessageText The message itself
     */
    private $alertMessageText;

    public function __construct(bool $alertMessage, string $alertMessageText)
    {
        $this->alertMessage = $alertMessage;
        $this->alertMessageText = $alertMessageText;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        // Pas de message à afficher ?
        if (!$this->alertMessage) {
            return;
        }

        // Pour ne gérer que la requête "principale"
        // @link https://symfony.com/doc/current/event_dispatcher.html#request-events-checking-types
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }

        // On exclu les routes du Profiler et de la WDT
        // $request->getPathInfo() contient la route
        // La Regex se trouve ici '/REGEX/'
        if (preg_match('/^\/(_profiler|_wdt)/', $event->getRequest()->getPathInfo())) {
            return;
        }

        // Requête XHR/Fetch ? (AJAX)
        if ($event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        // dump('Subscriber appelé...', $event);

        // Le contenu de la réponse qui se trouve dans l'événement reçu
        $response = $event->getResponse();
        $content = $response->getContent();

        // On modifie le contenu de la réponse
        $content = str_replace(
            '</nav>',
            '</nav><div class="container alert alert-danger mt-3">'.$this->alertMessageText.'</div>',
            $content
        );

        // On remplace le contenu de la réponse d'origine
        $response->setContent($content);

        // Symfony se charge du reste... l'objet $response a été manipulé directement
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
