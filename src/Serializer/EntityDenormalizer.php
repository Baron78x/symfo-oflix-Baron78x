<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Entity denormalizer
 */
class EntityDenormalizer implements DenormalizerInterface
{
    /** @var EntityManagerInterface **/
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        // On aura besoin de l'EM pour récupérer notre entité en base
        $this->em = $em;
    }

    /**
     * Ce denormalizer doit-il s'appliquer sur la donnée courante ?
     * Si oui, on appelle $this->denormalize()
     * 
     * $data => l'id du Genre si le JSON contient "genres": [1384, 1402]
     * => des ids qui sont dans votre BDD
     * $type => le type de la classe vers laquelle on souhaite dénormaliser $data
     * 
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        // Est-ce que la classe est de type Entité doctrine ?
        // Est-ce que la donnée fournie est numérique ?
        return strpos($type, 'App\\Entity\\') === 0 && (is_numeric($data));
    }

    /**
     * Cette méthode sera appelée si la condition du dessus est valide
     * 
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        // EntityManager->find() = raccourci vers le Repository->find()
        return $this->em->find($class, $data);
    }
}