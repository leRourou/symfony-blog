<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Like;
use DateTimeImmutable;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $users = [];
        $posts = [];

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
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeThisYear()))
                ->setContent($faker->realTextBetween(250, 500))
                ->setUser($faker->randomElement($users));
            $manager->persist($post);
            $posts[] = $post;
        }

        # Fake comments
        for ($i = 0; $i < 100; $i++) {
            $comment = new Comment();
            $comment
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeThisYear()))
                ->setContent($faker->realTextBetween(50, 200))
                ->setAuthor($faker->randomElement($users))
                ->setPost($faker->randomElement($posts));
            $manager->persist($comment);
        }

        # Fake likes 
        for ($i = 0; $i < 100; $i++) {
            $like = new Like();
            $like
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeThisYear()))
                ->setPost($faker->randomElement($posts))
                ->setUser($faker->randomElement($users));
            $manager->persist($like);
        }

        $manager->flush();
    }
}
