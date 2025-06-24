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
        // given

        $client = static::createClient();

        // when

        $client->request('GET', '/user');
        $responseCode = $client->getResponse()->getStatusCode();

        // then

        $this->assertEquals(404, $responseCode);
    }
}