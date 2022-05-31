<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Classe qui communique avec l'API OMBDAPI.com
 */
class OmdbApi
{
    private $httpClient;
    private $apiKey;

    public function __construct(HttpClientInterface $httpClient, $apiKey)
    {
        // On va utiliser le "client HTTP" depuis notre service
        // @link https://symfony.com/doc/current/http_client.html
        $this->httpClient = $httpClient;

        // La clé API pour OMDBAPI
        $this->apiKey = $apiKey;
    }

    /**
     * Exécuter une requête pour une titre donné
     * @deprecated 1.1
     */
    public function fetch(string $title)
    {
        // On envoie une requête vers l'API
        // Cette requête contient le titre du film
        $response = $this->httpClient->request(
            'GET',
            'http://www.omdbapi.com/',
            [
                // Définition des paramètres GET
                // @link https://symfony.com/doc/current/http_client.html#query-string-parameters
                'query' => [
                    'apiKey' => $this->apiKey,
                    't' => $title,
                ]
            ]
        );

        // On convertit la réponse en tableau PHP
        $content = $response->toArray();

        return $content;
    }

    /**
     * Récupère le poster d'un film donné
     * 
     * @param string $title Titre du film à trouver
     * 
     * @return string|null URL du poster ou null si non trouvé
     */
    public function fetchPoster(string $title)
    {
        // On va chercher les infos du film
        $content = $this->fetch($title);

        // La clé "Poster" est-elle absente du le contenu reçu ?
        if (!array_key_exists('Poster', $content)) {
            return null;
        }

        return $content['Poster'];
    }
}