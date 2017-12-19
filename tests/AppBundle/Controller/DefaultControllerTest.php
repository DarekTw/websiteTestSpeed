<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to XSOLVE website time loading benchmark', $crawler->filter('#container h1')->text());
    }

    public function testSubmit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Submit')->form();
        
        // set some values
        $form['website_form[website]'] = 'https://xsolve.software/';

        // submit the form
        $crawler = $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertRegExp('/Website tested: https:\/\/xsolve.software\/ : [\d]+\.[\d]+s\./', $crawler->filter('#website-info')->text());
    }

}
