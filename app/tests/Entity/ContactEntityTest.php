<?php
namespace App\Tests\Entity;

use App\Entity\Category;
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
    public function TestGetSetData(): void
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