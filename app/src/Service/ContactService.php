<?php
/**
 * Contact service.
 */

namespace App\Service;

use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ContactService.
 */
class ContactService implements ContactServiceInterface
{
    public $tagService;

    /**
     * Constructor.
     *
     * @param PaginatorInterface $paginator         Paginator
     * @param ContactRepository  $contactRepository Contact repository
     */
    public function __construct(private readonly PaginatorInterface $paginator, private readonly ContactRepository $contactRepository)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int   $page    Page number
     * @param User  $author  Author
     * @param array $filters Filters
     *
     * @return PaginationInterface<string, mixed> Paginated list
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, User $author, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->contactRepository->queryByAuthor($author, $filters),
            $page,
            ContactRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Contact $contact Contact entity
     */
    public function save(Contact $contact): void
    {
        $this->contactRepository->save($contact);
    }

    /**
     * Delete entity.
     *
     * @param Contact $contact Contact entity
     */
    public function delete(Contact $contact): void
    {
        $this->contactRepository->delete($contact);
    }

    /**
     * Prepare pattern.
     *
     * @param string $pattern Pattern for searching
     *
     * @return string Result pattern
     */
    public function preparePattern(string $pattern): string
    {
        return $pattern;
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     *
     * @throws NonUniqueResultException
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];

        if (!empty($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if ($tag instanceof \App\Entity\Tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }
}
