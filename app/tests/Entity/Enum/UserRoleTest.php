<?php
/**
 * This test file is a part of the ZTP2 project.
 *
 * (c) Filip Krzych <filip.krzych@student.uj.edu.pl>
 */

namespace App\Tests\Entity\Enum;

use App\Entity\Enum\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * UserRole enum tests.
 */
class UserRoleEntityTest extends TestCase
{
    /**
     * Test UserRole values.
     */
    public function testUserRoleValues(): void
    {
        $this->assertEquals('ROLE_USER', UserRole::ROLE_USER->value);
        $this->assertEquals('ROLE_ADMIN', UserRole::ROLE_ADMIN->value);
    }

    /**
     * Test UserRole labels.
     */
    public function testUserRoleLabels(): void
    {
        $this->assertEquals('label.role_user', UserRole::ROLE_USER->label());
        $this->assertEquals('label.role_admin', UserRole::ROLE_ADMIN->label());
    }
}
