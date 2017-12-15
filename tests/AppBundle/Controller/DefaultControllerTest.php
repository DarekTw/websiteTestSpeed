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
        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }
}

// test service
/*
 * check is valid domain exception
 * check test domain function | return time or exception 
 * chcek test domains function | 
*/

/*
 * class domain
 * var domain name
 * var load time
 * function test speed
 * 
 * service domainSpeedCoparator
 * var main domain
 * array od domain competitors
 * function compare
 * displayText result
 * dsplayHtml result
 * 
 * class Exception
 * 
 * class SMSAPI
 * function send Sms
 * 
 */