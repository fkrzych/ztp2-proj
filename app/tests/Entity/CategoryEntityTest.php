<?php
/**
 * This test file is a part of the ZTP2 project.
 *
 * (c) Filip Krzych <filip.krzych@student.uj.edu.pl>
 */

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

/**
 * Category class tests.
 */
class CategoryEntityTest extends TestCase
{
    /**
     * Test can get and set data.
     */
    public function testGetSetData(): void
    {
        $testedCategory = new Category();
        $testedCategory->setName('Testing Category');
        $testedCategory->setSlug('testing-category');


        self::assertSame('Testing Category', $testedCategory->getName());
        self::assertSame('testing-category', $testedCategory->getSlug());
        self::assertSame($testedCategory->getId(), $testedCategory->getId());
    }
}
