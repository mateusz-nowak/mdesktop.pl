<?php
namespace Acme\MainBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Acme\MainBundle\Entity\Category;
use Buzz\Browser;
use Symfony\Component\DomCrawler\Crawler;

class LoadMovieCategories implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        header('Content-Type: text/html; charset=utf-8');
        mb_internal_encoding('utf-8');

        $stackCategory = array();
        $buzzBrowser = new Browser;
        $response = $buzzBrowser->get('http://www.seans-online.eu/filmy.html');

        $crawler = new Crawler;
        $crawler->addHtmlContent($response->__toString());

        foreach ($crawler->filter('#left-box-cat a') as $linkItem) {
            preg_match('/(?P<name>.*?)\s\(/', $linkItem->nodeValue, $res);

            $stackCategory[] = $res['name'];
        }

        $stackCategory = array_slice($stackCategory, 0, -2);

        for ($i = 0; $i < count($stackCategory); ++$i) {
            $category = new Category;
            $category->setName($stackCategory[$i]);

            $manager->persist($category);

        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2; // number in which order to load fixtures
    }

}
