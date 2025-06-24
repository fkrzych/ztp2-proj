<?php
/**
 * Event service tests.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Entity\Enum\UserRole;
use App\Entity\Event;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EventService;
use App\Service\EventServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class EventServiceTest.
 */
class EventServiceTest extends KernelTestCase
{
    /**
     * Event repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Event service.
     */
    private ?EventServiceInterface $eventService;

    /**
     * Set up test.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->eventService = $container->get(EventService::class);
    }

    /**
     * Test save.
     *s
     * @throws ORMExceptions
     */
    public function testSave(): void
    {
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $category = new Category();
        $category->setName('Test Category');
        $this->entityManager->persist($category);
        $expectedEvent = new Event();
        $expectedEvent->setName('Test Event');
        $expectedEvent->setDate(new \DateTime('now'));
        $expectedEvent->setAuthor($user);
        $expectedEvent->setCategory($category);

        $this->eventService->save($expectedEvent);

        $expectedEventId = $expectedEvent->getId();
        $resultEvent = $this->entityManager->createQueryBuilder()
            ->select('event')
            ->from(Event::class, 'event')
            ->where('event.id = :id')
            ->setParameter(':id', $expectedEventId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedEvent, $resultEvent);
    }

    /**
     * Test delete.
     *
     * @throws OptimisticLockException|ORMException
     */
    public function testDelete(): void
    {
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $category = new Category();
        $category->setName('Test Category');
        $this->entityManager->persist($category);

        $eventToDelete = new Event();
        $eventToDelete->setName('Test Event');
        $eventToDelete->setDate(new \DateTime('now'));
        $eventToDelete->setAuthor($user);
        $eventToDelete->setCategory($category);
        $this->entityManager->persist($eventToDelete);
        $this->entityManager->flush();
        $deletedEventId = $eventToDelete->getId();

        $this->eventService->delete($eventToDelete);

        $resultEvent = $this->entityManager->createQueryBuilder()
            ->select('event')
            ->from(Event::class, 'event')
            ->where('event.id = :id')
            ->setParameter(':id', $deletedEventId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultEvent);
    }

    /**
     * Test pagination empty list.
     */
    public function testGetPaginatedList(): void
    {
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $category = new Category();
        $category->setName('Test Category');
        $this->entityManager->persist($category);

        $counter = 0;
        while ($counter < $dataSetSize) {
            $event = new Event();
            $event->setName('Test Event #'.$counter);
            $event->setDate(new \DateTime('now'));
            $event->setAuthor($user);
            $event->setCategory($category);
            $this->eventService->save($event);

            ++$counter;
        }

        $result = $this->eventService->getPaginatedList($page, $user);

        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Create user for testing purpose.
     */
    private function createUser(array $roles): User
    {
        $hasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles($roles);
        $user->setPassword(
            $hasher->hashPassword(
                $user,
                'p@55w0rd'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }
}
