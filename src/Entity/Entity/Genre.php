<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
// Une référence au dossier "Mapping" du vendor Doctrine
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Cette classe représente l'entité "Genre"
 * donc la table "genre"
 * 
 * Différence avec Active Record :
 * Cette classe est une "POPO" => Plain Old PHP Object
 * elle n'hérite d'aucune classe
 */

 /**
  * @ORM\Entity
  */
class Genre
{
    /**
     * @var int Primary key
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies_get_item"})
     */
    private $id;

    /**
     * @var string Name
     * 
     * @ORM\Column(type="string", length=50, unique=true)
     * @Groups({"movies_get_item"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, mappedBy="genres")
     */
    private $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    /**
     * Get primary key
     *
     * @return int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name Name
     *
     * @return self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->addGenre($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeGenre($this);
        }

        return $this;
    }
}