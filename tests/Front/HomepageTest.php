<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomepageTest extends WebTestCase
{
    public function testHomePageHas5Movies(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // as ton bien recu une page avec un code HTTP 200
        $this->assertResponseIsSuccessful();
        // on vérifie la punch line du site 
        $this->assertSelectorTextContains('p.lead', 'Où que vous soyez. Gratuit pour toujours.');

        // 5 films récents s'affichent
        // .movie__poster
        $filteredCrawler = $crawler->filter('div.movie__poster');
        $this->assertEquals(5, count($filteredCrawler));

    }

    public function testAdd2FavoriteFromHomepage() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // select the button
        // dump(count($crawler->filter('.bi-bookmark-plus')->eq(0)));

        $buttonCrawlerNode = $crawler->filter('.movie__favorite > button')->eq(0);

        // // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();
        
        // submit the Form object
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // on suit la redirection
        $crawler = $client->followRedirect();

        // est ce que j'ai le flash message
        $this->assertSelectorTextContains('div.alert-success', 'a été ajouté de votre liste de favoris.');
        
        // est ce qu'on est sur la route /favorites

        // est ce qu'il y a bien 1 film

    }
}
