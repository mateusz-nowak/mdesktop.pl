<?php

namespace Acme\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Acme\MainBundle\Entity\Category;
use Acme\MainBundle\Entity\Movie;
use Buzz\Browser;

class MoviesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('main:movies')
            ->setDescription('Search for movies update');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $browser = new Browser;
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        for ($i = 1; $i <= 1351; ++$i) {
            echo sprintf(">> Loading %d of %d movies...\n", $i*10, 13510);

            $response = $browser->get(sprintf('http://kinoland.pl/videos?page=%d', $i));
            $crawler = new Crawler;
            $crawler->addHtmlContent((string) $response);

            foreach ($crawler->filter('.film-main') as $movie) {
                preg_match('/Kategorie: (?P<categories>.*)?/', $movie->getElementsByTagName('div')->item(1)->nodeValue, $tmpCategory);
                preg_match('/(?<title>.*?) \[(?<translation>.*?)\]/', $movie->getElementsByTagName('h2')->item(0)->nodeValue, $tmpData);
                preg_match('/film\_online\/(?P<href>\d+)/', $movie->getElementsByTagName('a')->item(0)->getAttribute('href'), $tmpDataHref);

                $responseInfo = $browser->get(sprintf('http://kinoland.pl/ajax.php?mode=movie&function=limit2&movieId=%s', $tmpDataHref['href']));
                preg_match('/e=(?P<embed>.*?)\?/', $responseInfo, $tmpEmbed);

                if ($em->getRepository('AcmeMainBundle:Movie')->findOneByRemoteKey($tmpDataHref['href'])) {
                    continue;
                }

                if (!isset($tmpEmbed['embed']) || !isset($tmpDataHref['href']) || !isset($tmpData['translation']) || !isset($tmpData['title'])) {
                    continue;
                }

                $movieEntity = new Movie;
                $movieEntity->setTitle($tmpData['title']);
                $movieEntity->setText($movie->getElementsByTagName('div')->item(5)->nodeValue);
                $movieEntity->setPhoto($movie->getElementsByTagName('img')->item(0)->getAttribute('src'));
                $movieEntity->setTranslation($tmpData['translation']);
                $movieEntity->setEmbed($tmpEmbed['embed']);
                $movieEntity->setRemoteKey($tmpDataHref['href']);
                $movieEntity->setRatingCount(0);
                $movieEntity->setRatingValue(5);

                foreach (explode(',', str_replace(', ', ',', $tmpCategory['categories'])) as $catItem) {
                    $categoryName = trim($catItem);

                    $category = $em->getRepository('AcmeMainBundle:Category')->findOneByName($categoryName);

                    if (!$category) {
                        $category = new Category;
                        $category->setName($categoryName);
                        $category->setType(2);

                        $em->persist($category);
                    }

                    $movieEntity->addCategory($category);
                }

                $em->persist($movieEntity);
                $em->flush();
            }
        }
    }
}
