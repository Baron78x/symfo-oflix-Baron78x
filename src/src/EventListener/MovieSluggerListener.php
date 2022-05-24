<?php

namespace App\EventListener;

use App\Entity\Movie;
use App\Service\MySlugger;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MovieSluggerListener
{
    // Le Slugger à utiliser dans ce service
    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function setSlug(Movie $movie, LifecycleEventArgs $event): void
    {
        // Slugifier le titre
        $movie->setSlug($this->slugger->slugify($movie->getTitle()));
    }
}