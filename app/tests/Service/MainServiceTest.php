<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\EventRepository;
use App\Service\MainService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

class MainServiceTest extends TestCase
{
    private EventRepository $eventRepository;
    private PaginatorInterface $paginator;
    private MainService $mainService;

    protected function setUp(): void
    {
        $this->eventRepository = $this->createMock(EventRepository::class);
        $this->paginator = $this->createMock(PaginatorInterface::class);

        $this->mainService = new MainService(
            $this->eventRepository,
            $this->paginator
        );
    }

    public function testGetPaginatedList(): void
    {
        $page = 1;
        $user = new User();
        $queryBuilderMock = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $paginationMock = $this->createMock(PaginationInterface::class);

        $this->eventRepository
            ->method('queryByAuthorCurrent')
            ->with($user)
            ->willReturn($queryBuilderMock);

        $this->paginator
            ->method('paginate')
            ->with($queryBuilderMock, $page, $this->anything())
            ->willReturn($paginationMock);

        $result = $this->mainService->getPaginatedList($page, $user);

        $this->assertSame($paginationMock, $result);
    }

    public function testIfCurrentsExistReturns1(): void
    {
        $user = new User();

        $this->eventRepository
            ->method('countCurrent')
            ->with($user)
            ->willReturn(['some_data']);

        $result = $this->mainService->ifCurrentsExist($user);

        $this->assertEquals(1, $result);
    }

    public function testIfCurrentsExistReturns0(): void
    {
        $user = new User();

        $this->eventRepository
            ->method('countCurrent')
            ->with($user)
            ->willReturn([]);

        $result = $this->mainService->ifCurrentsExist($user);

        $this->assertEquals(0, $result);
    }
}
