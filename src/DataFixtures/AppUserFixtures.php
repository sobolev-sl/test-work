<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


/**
 * Class AppUserFixtures
 * @package App\DataFixtures
 */
class AppUserFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('test1@mts.ru');
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('test2@mts.ru');
        $manager->persist($user);
        $manager->flush();
    }
}
