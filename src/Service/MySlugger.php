<?php

namespace App\Service;

use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Notre Slugger basé sur le Slugger de Symfony
 */
class MySlugger
{
    /** @var SluggerInterface $slugger Symfony Slugger */
    private $slugger;

    /** @var bool $toLower Convert the slug to lower or not */
    private $toLower;

    public function __construct(SluggerInterface $slugger, bool $toLower)
    {
        $this->slugger = $slugger;
        $this->toLower = $toLower;
    }

    /**
     * "Slugifie" une chaine
     * 
     * @param string $string La chaîne à transformer
     * 
     * @return AbstractUnicodeString
     */
    public function slugify(string $string): AbstractUnicodeString
    {
        // On slugifie la chaine demandée
        $slug = $this->slugger->slug($string);

        // Lowercase ?
        if ($this->toLower) {
            $slug = $slug->lower();
        }

        return $slug;
    }
}