<?php

namespace Webclusive\Bundle\LunchBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StateControllerTest extends WebTestCase
{
    public function testSet()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/set');
    }

}
