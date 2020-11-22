<?php

namespace App\DataFixtures;

use App\Entity\Estimate;
use App\Entity\FixedCostLineItem;
use App\Entity\HourRangeLineItem;
use App\Entity\Section;
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

        $estimate = (new Estimate())->setName('Test Estimate')->setCreatedBy($user)->setDefaultRate(125);

        $section1 = (new Section())->setName('Section 1')->setEstimate($estimate)->setSortOrder(1)->setCreatedBy($user);
        $section2 = (new Section())->setName('Section 2')->setEstimate($estimate)->setSortOrder(2)->setCreatedBy($user);

        $li1 = (new HourRangeLineItem('Item 1', 20, 40, 2, 1, null, null))->setCreatedBy($user);
        $li2 = (new FixedCostLineItem('Item 2', 99.45, 2))->setCreatedBy($user);

        $section1->addLineItem($li1);
        $section1->addLineItem($li2);

        $manager->persist($user);
        $manager->persist($estimate);
        $manager->persist($section1);
        $manager->persist($section2);
        $manager->persist($li1);
        $manager->persist($li2);
        $manager->flush();
    }
}
