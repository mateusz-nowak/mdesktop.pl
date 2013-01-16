<?php
namespace Acme\MainBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Acme\MainBundle\Entity\Category;

class LoadCategories implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $categories = array(
            'Podstrony dynamiczne CMS',
            'Wiadomości',
        );

        foreach ($categories as $category) {
            $categoryEntity = new Category();
            $categoryEntity->setName($category);
            $categoryEntity->setType(1);

            $manager->persist($categoryEntity);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2; // number in which order to load fixtures
    }
}
