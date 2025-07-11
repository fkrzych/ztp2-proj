<?php
/**
 * This test file is a part of the ZTP2 project.
 *
 * (c) Filip Krzych <filip.krzych@student.uj.edu.pl>
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Service\CategoryService;
use App\Service\CategoryServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CategoryServiceTest.
 */
class CategoryServiceTest extends KernelTestCase
{
    /**
     * Category repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Category service.
     */
    private ?CategoryServiceInterface $categoryService;

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
        $this->categoryService = $container->get(CategoryService::class);
    }

    /**
     * Test save.
     *s.
     *
     * @throws ORMExceptions
     */
    public function testSave(): void
    {
        $expectedCategory = new Category();
        $expectedCategory->setName('Test Category');

        $this->categoryService->save($expectedCategory);

        $expectedCategoryId = $expectedCategory->getId();
        $resultCategory = $this->entityManager->createQueryBuilder()
            ->select('category')
            ->from(Category::class, 'category')
            ->where('category.id = :id')
            ->setParameter(':id', $expectedCategoryId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedCategory, $resultCategory);
    }

    /**
     * Test delete.
     *
     * @throws OptimisticLockException|ORMException
     */
    public function testDelete(): void
    {
        $categoryToDelete = new Category();
        $categoryToDelete->setName('Test Category');
        $this->entityManager->persist($categoryToDelete);
        $this->entityManager->flush();
        $deletedCategoryId = $categoryToDelete->getId();

        $this->categoryService->delete($categoryToDelete);

        $resultCategory = $this->entityManager->createQueryBuilder()
            ->select('category')
            ->from(Category::class, 'category')
            ->where('category.id = :id')
            ->setParameter(':id', $deletedCategoryId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultCategory);
    }

    /**
     * Test find by id.
     *
     * @throws ORMException
     */
    public function testFindById(): void
    {
        $expectedCategory = new Category();
        $expectedCategory->setName('Test Category');
        $this->entityManager->persist($expectedCategory);
        $this->entityManager->flush();
        $expectedCategoryId = $expectedCategory->getId();

        $resultCategory = $this->categoryService->findOneById($expectedCategoryId);

        $this->assertEquals($expectedCategory, $resultCategory);
    }

    /**
     * Test pagination empty list.
     */
    public function testGetPaginatedList(): void
    {
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $category = new Category();
            $category->setName('Test Category #'.$counter);
            $this->categoryService->save($category);

            ++$counter;
        }

        $result = $this->categoryService->getPaginatedList($page);

        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Test if category can be deleted returns False On No Result Exception.
     */
    public function testCanBeDeletedReturnsFalseOnNoResultException(): void
    {
        $mockEventRepo = $this->createMock(\App\Repository\EventRepository::class);
        $mockEventRepo->method('countByCategory')->willThrowException(new \Doctrine\ORM\NoResultException());

        $mockCategoryRepo = $this->createMock(\App\Repository\CategoryRepository::class);
        $mockPaginator = $this->createMock(\Knp\Component\Pager\PaginatorInterface::class);

        $service = new CategoryService($mockCategoryRepo, $mockEventRepo, $mockPaginator);

        $category = new Category();
        $this->assertFalse($service->canBeDeleted($category));
    }

    /**
     * Test if category can be deleted returns False On Non Unique Result Exception.
     */
    public function testCanBeDeletedReturnsFalseOnNonUniqueResultException(): void
    {
        $mockEventRepo = $this->createMock(\App\Repository\EventRepository::class);
        $mockEventRepo->method('countByCategory')->willThrowException(new \Doctrine\ORM\NonUniqueResultException());

        $mockCategoryRepo = $this->createMock(\App\Repository\CategoryRepository::class);
        $mockPaginator = $this->createMock(\Knp\Component\Pager\PaginatorInterface::class);

        $service = new CategoryService($mockCategoryRepo, $mockEventRepo, $mockPaginator);

        $category = new Category();
        $this->assertFalse($service->canBeDeleted($category));
    }
}
