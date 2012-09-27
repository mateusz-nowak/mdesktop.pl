<?php
namespace Acme\MainBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Acme\MainBundle\Entity\Category;
use Buzz\Browser;
use Symfony\Component\DomCrawler\Crawler;
use Acme\MainBundle\Entity\Movie;

class LoadMovie implements FixtureInterface
{

    const SITE_BASE_URL = 'http://www.seans-online.eu';

    public function load(ObjectManager $manager)
    {
        header('Content-Type: text/html; charset=utf-8');
        mb_internal_encoding('utf-8');

        $stackMovie = array();

        //for($p = 1; $p <= 791; ++$p)
        for ($p = 1; $p < 3; ++$p) {
            $buzzBrowser = new Browser;
            $response = $buzzBrowser->get(sprintf('%s/filmy-%s.html', self::SITE_BASE_URL, $p));

            $crawler = new Crawler;
            $crawler->addHtmlContent($response->__toString());

            $movieID = 0;
            foreach ($crawler->filter('.film-list-info') as $translation) {
                preg_match('/Tłumaczenie\: (?P<trans>.*?) Rok/', $translation->nodeValue, $translationTmp);
                $stackMovie[$movieID++]['translation'] = ucwords(trim($translationTmp['trans']));
            }

            $attrNextID = 0;
            $movieID = 0;
            foreach ($crawler->filter('.film-list-button a') as $button) {
                if(++$attrNextID % 2) continue;

                $stackMovie[$movieID++]['url'] = self::SITE_BASE_URL . $button->getAttribute('href');
            }

            foreach ($stackMovie as $movieID => $movieEntity) {
                $buzzBrowser = new Browser;
                $response = $buzzBrowser->get($movieEntity['url']);

                $crawler = new Crawler;
                $crawler->addHtmlContent($response->__toString());

                preg_match('/http\:\/\/www\.megustavid\.com\/e\=(?P<key>.*?)\?/', $response, $movieUrl);

                if (!isset($movieUrl['key']) || !$movieUrl['key']) {
                    continue;
                }

                $stackMovie[$movieID]['body'] = $crawler->filter('meta')->eq(0)->attr('content');
                $stackMovie[$movieID]['category'] = $crawler->filter('#film-help')->filter('span')->eq(2)->text();
                $stackMovie[$movieID]['key'] = $movieUrl['key'];
                $stackMovie[$movieID++]['title'] = ucwords($crawler->filter('h1')->text());
            }

            $stackMovie = array_filter($stackMovie, function($item) {
                return isset($item['key']) && isset($item['category']) && isset($item['title']);
            });

            foreach ($stackMovie as $movieEntity) {
                $movie = new Movie();
                $movie->setTitle($movieEntity['title']);
                $movie->setBody($movieEntity['body']);
                $movie->setTranslation($movieEntity['translation']);
                $movie->setCategory($movieEntity['category']);
                $movie->setRemoteKey($movieEntity['key']);
                $movie->setRatingCount(0);
                $movie->setRatingValue(3);

                $manager->persist($movie);
            }

            $manager->flush();
            $stackMovie = array();
        }
    }
//
//
//    protected function loadCategories(ObjectManager $manager)
//    {
//        header('Content-Type: text/html; charset=utf-8');
//        mb_internal_encoding('utf-8');
//
//        $stackCategory = array();
//        $buzzBrowser = new Browser;
//        $response = $buzzBrowser->get('http://www.seans-online.eu/filmy.html');
//
//        $crawler = new Crawler;
//        $crawler->addHtmlContent($response->__toString());
//
//        foreach ($crawler->filter('#left-box-cat a') as $linkItem) {
//            preg_match('/(?P<name>.*?)\s\(/', $linkItem->nodeValue, $res);
//
//            $stackCategory[] = $res['name'];
//        }
//
//        $stackCategory = array_slice($stackCategory, 0, -2);
//
//        for ($i = 0; $i < count($stackCategory); ++$i) {
//            $category = new Category;
//            $category->setName($stackCategory[$i]);
//
//            $manager->persist($category);
//
//        }
//
//        $manager->flush();
//    }

    public function getOrder()
    {
        return 5;
    }

}
