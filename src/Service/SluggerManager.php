<?php

namespace App\Service;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Manage slugs (movies and series name) in url
 */
class SluggerManager
{
    private $slugger;
    private $lowerEnabled;

    

    public function __construct(SluggerInterface $slugger, $lowerEnabled)
    {
        $this->slugger = $slugger;
        $this->lower = $lowerEnabled;
    }

    public function lower($title)
    {

        return $this->slugger->slug($title)->lower()->__toString();
    }
}
