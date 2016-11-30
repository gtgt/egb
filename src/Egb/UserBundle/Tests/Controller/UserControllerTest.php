<?php

namespace Egb\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/1');

        $this->assertTrue($crawler->filter('html:contains("user")')->count() > 0);
    }
}
