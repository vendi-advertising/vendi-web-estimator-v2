<?php

namespace App\DataFixtures;

use App\Entity\Estimate;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User('chris@vendiadvertising.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));

        $estimate = (new Estimate())->setName('Test Estimate')->setCreatedBy($user);

        $manager->persist($user);
        $manager->persist($estimate);
        $manager->flush();
    }
}
