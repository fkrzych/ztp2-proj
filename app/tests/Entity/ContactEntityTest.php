<?php
/**
 * This test file is a part of the ZTP2 project.
 *
 * (c) Filip Krzych <filip.krzych@student.uj.edu.pl>
 */

namespace App\Tests\Entity;

use App\Entity\Contact;
use App\Entity\User;
use Monolog\Test\TestCase;

/**
 * Post class tests.
 */
class ContactEntityTest extends TestCase
{
    /**
     * Test can get and set data.
     */
    public function testGetSetData(): void
    {
        $testedContact = new Contact();
        $testedContact->setName('Mariusz');
        $testedContact->setPhone('123456789');
        $testedContact->setAuthor(new User());

        self::assertSame('Mariusz', $testedContact->getName());
        self::assertSame('123456789', $testedContact->getPhone());
        self::assertSame($testedContact->getAuthor(), $testedContact->getAuthor());
    }
}
