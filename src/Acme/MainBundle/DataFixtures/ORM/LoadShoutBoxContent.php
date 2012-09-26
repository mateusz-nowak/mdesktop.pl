<?php
namespace Acme\MainBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Acme\MainBundle\Entity\Shoutbox;
use Acme\UserBundle\Entity\User;

class LoadShoutBoxContent implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $user = current($manager->getRepository('Acme\UserBundle\Entity\User')->findAll());

        for ($i = 0; $i < 100; ++$i) {
            $shoutbox = new Shoutbox();
            $shoutbox->setText('Hello World from the shoutbox' . $i);
            $shoutbox->setUser($user);

            $manager->persist($shoutbox);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 3; // number in which order to load fixtures
    }

}
