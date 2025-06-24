<?php
/**
 * User Controller Test.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends WebTestCase
{
    /**
     * User Contact Route.
     */
    public function testControllerRoute(): void
    {
        $client = static::createClient();

        $client->request('GET', '/user');
        $responseCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(404, $responseCode);
    }
}