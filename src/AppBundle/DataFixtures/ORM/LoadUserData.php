<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userNames = [
            'Kelly',
            'John',
            'Donald',
            'Ariel',
            'Aivaras',
        ];

        foreach ($userNames as $userName) {
            $user = new User();
            $user->setName($userName);
            $user->setEmail(lcfirst($userName) . '@gmail.com');

            $manager->persist($user);
        }

        $manager->flush();
    }
}
