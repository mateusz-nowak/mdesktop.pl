<?php
namespace Acme\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Acme\UserBundle\Entity\User;

class LoadUserAdmin implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();

            $user->setUsername('administrator' . $i);
            $user->setEmail('administrator'.$i.'@example.com');
            $user->setPlainPassword('administrator'. $i);
            $user->addRole('ROLE_ADMIN');
            $user->setEnabled(true);

            $manager->persist($user);
            $manager->flush();
        }

    }
}
