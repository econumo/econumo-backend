<?php

namespace App\DataFixtures;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User(
            new Id('7c884d22-438d-47b7-a59e-e04a7bf028eb'),
            '1@user',
            new DateTime('2020-05-04 21:39:00')
        );
        $user->updatePassword($this->passwordEncoder->encodePassword($user, 'pass'));
        $manager->persist($user);

        $manager->flush();
    }
}
