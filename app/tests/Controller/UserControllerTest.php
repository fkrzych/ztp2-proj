<?php
/**
 * This test file is a part of the ZTP2 project.
 *
 * (c) Filip Krzych <filip.krzych@student.uj.edu.pl>
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
