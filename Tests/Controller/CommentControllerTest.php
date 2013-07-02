<?php

namespace Osbekro\CommentsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentControllerTest extends WebTestCase
{
    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/comments/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
