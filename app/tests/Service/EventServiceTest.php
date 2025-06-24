<?php
/**
 * Event service tests.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Entity\Event;
use App\Service\CategoryServiceInterface;
use App\Service\EventService;
use App\Service\EventServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\ORM\Exception\ORMException;
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
        // given
        $expectedEvent = new Event();
        $expectedEvent->setName('Test Event');

        // when
        $this->eventService->save($expectedEvent);

        // then
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
        // given
        $eventToDelete = new Event();
        $eventToDelete->setName('Test Event');
        $this->entityManager->persist($eventToDelete);
        $this->entityManager->flush();
        $deletedEventId = $eventToDelete->getId();

        // when
        $this->eventService->delete($eventToDelete);

        // then
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
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $event = new Event();
            $event->setName('Test Event #'.$counter);
            $this->eventService->save($event);

            ++$counter;
        }

        // when
        $result = $this->eventService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    public function testPrepareFiltersReturnsCategoryWhenValidCategoryIdProvided(): void
    {
        // Arrange
        $filters = ['category_id' => 1];
        $mockCategory = $this->createMock(Category::class);

        $categoryServiceMock = $this->createMock(CategoryServiceInterface::class);
        $categoryServiceMock->method('findOneById')
            ->with(1)
            ->willReturn($mockCategory);

        $eventService = $this->getMockBuilder(EventService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getTagService']) // assume your real service needs more deps
            ->getMock();

        // Inject the categoryService via reflection since it's private & readonly
        $ref = new \ReflectionClass($eventService);
        $prop = $ref->getProperty('categoryService');
        $prop->setAccessible(true);
        $prop->setValue($eventService, $categoryServiceMock);

        // Act
        $result = $eventService->prepareFilters($filters);

        // Assert
        $this->assertArrayHasKey('category', $result);
        $this->assertSame($mockCategory, $result['category']);
    }
}

