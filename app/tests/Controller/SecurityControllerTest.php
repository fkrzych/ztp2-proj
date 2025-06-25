<?php
/**
 * This test file is a part of the ZTP2 project.
 *
 * (c) Filip Krzych <filip.krzych@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest.
 */
class SecurityControllerTest extends WebTestCase
{
    /**
     * Set up function.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test Login Route.
     */
    public function testLoginRoute(): void
    {
        $expectedStatusCode = 200;

        $this->httpClient->request('GET', '/login');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        $this->assertEquals($statusCode, $expectedStatusCode);
    }

    /**
     * Test logout route.
     */
    public function testLogoutRoute(): void
    {
        $expectedStatusCode = 302;

        $this->httpClient->request('GET', '/logout');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        $this->assertEquals($statusCode, $expectedStatusCode);
    }

    /**
     * Test Logout Method Throws Logic Exception.
     */
    public function testLogoutMethodThrowsLogicException(): void
    {
        $this->expectException(\LogicException::class);

        $controller = new \App\Controller\SecurityController();
        $controller->logout();
    }
}
