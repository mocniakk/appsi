<?php
/**
 * Event entity.
 */

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Event.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'events')]
class Event
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Start date.
     */
    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $startDate;

    /**
     * End date.
     */
    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $endDate;

    /**
     * Title.
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title = null;

    /**
     * Category.
     */
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for start date.
     *
     * @return \DateTime|null StartDate
     */
    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    /**
     * Setter for start date.
     *
     * @param \DateTime $startDate StartDate
     */
    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * Getter for end date.
     *
     * @return \DateTime|null EndDate
     */
    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    /**
     * Setter for end date.
     *
     * @param \DateTime $endDate EndDate
     */
    public function setEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string|null $title Title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for category.
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category.
     *
     * @param Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }
}
