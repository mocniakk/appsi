<?php

/**
 * Contact fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ContactFixtures.
 */
class ContactFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(10, 'contacts', function (int $i) {
            $contact = new Contact();
            $contact->setName($this->faker->word);
            $contact->setPhoneNumber($this->faker->phoneNumber);
            $contact->setEmail($this->faker->sentence);

            return $contact;
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
