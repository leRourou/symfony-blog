<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\User;
use DateTimeImmutable;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $users = [];

        # Fake users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user
                ->setFirstName($faker->firstName())
                ->setName($faker->lastName())
                ->setPseudo($faker->userName());
            $manager->persist(object: $user);
            $users[] = $user;
        }

        # Fake posts
        for ($i = 0; $i < 50; $i++) {
            $post = new Post();
            $post
                ->setLikeCount($faker->numberBetween(0, 100))
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeThisYear()))
                ->setContent($faker->realTextBetween(250, 500))
                ->setUser($faker->randomElement($users));
            $manager->persist($post);
        }
        $manager->flush();
    }
}
