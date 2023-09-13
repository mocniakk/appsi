<?php
/**
 * Event fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Event;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class EventFixtures.
 */
class EventFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(10, 'events', function (int $i) {
            $event = new Event();
            $event->setTitle($this->faker->sentence);
            $event->setStartDate($this->faker->dateTime);
            $event->setEndDate($this->faker->dateTime);

            //  $event->setStartDate(
            //     DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-10 days', '+50 days'))

            //  );
            //  $event->setEndDate(
            //       DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-10 days', '+70 days'))
            //  );

            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $event->setCategory($category);

            return $event;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
