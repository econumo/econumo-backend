<?php

namespace App\DataFixtures;

use App\Domain\Factory\UserFactoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private UserFactoryInterface $userFactory;

    public function __construct(UserFactoryInterface $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    public function load(ObjectManager $manager)
    {
        $user = $this->userFactory->create('Dmitry', 'dmitry@econumo', 'pass');
        $manager->persist($user);

        $manager->flush();
    }
}
