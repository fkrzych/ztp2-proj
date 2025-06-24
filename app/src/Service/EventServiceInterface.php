<?php
/**
 * Event service interface.
 */

namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface EventServiceInterface.
 */
interface EventServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int   $page    Page number
     * @param User  $author  User
     * @param array $filters Filters
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, User $author, array $filters = []): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Event $event Event entity
     */
    public function save(Event $event): void;

    /**
     * Delete entity.
     *
     * @param Event $event Event entity
     */
    public function delete(Event $event): void;
}
