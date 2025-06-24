<?php
/**
 * Security Controller Test.
 */

namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Repository\UserRepository;
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
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', '/login');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($statusCode, $expectedStatusCode);
    }

    /**
     * Test logout route.
     */
    public function testLogoutRoute(): void
    {
        // given
        $expectedStatusCode = 302;

        // when
        $this->httpClient->request('GET', '/logout');
        $statusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($statusCode, $expectedStatusCode);
    }

    public function testLogoutMethodThrowsLogicException(): void
    {
        $this->expectException(\LogicException::class);

        $controller = new \App\Controller\SecurityController();
        $controller->logout(); // Directly call the method
    }
}