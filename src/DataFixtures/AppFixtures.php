<?php

namespace App\DataFixtures;

use App\Factory\ApiTokenFactory;
use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TaskFactory::createMany(50);

        UserFactory::createMany(3);


        ApiTokenFactory::createMany(8, function (){
            return [
                'token' => uniqid('apitoken_', true)
            ];
        });

        ApiTokenFactory::createMany(4, function (){
            return [
                'token' => uniqid('apitoken_', true),
                'expiresAt' => \DateTimeImmutable::createFromMutable(
                    new \DateTime('+10 days')
                )
            ];
        });

    }
}
