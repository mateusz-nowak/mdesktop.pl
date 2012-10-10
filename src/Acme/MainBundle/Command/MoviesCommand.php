<?php

namespace Acme\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DomCrawler\Crawler;
use Acme\MainBundle\Entity\Category;
use Acme\MainBundle\Entity\Movie;
use Buzz\Browser;
use Exception;

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
        $categoryStack = array();
        
        for($i = 1; $i <= 1351; ++$i)
        {
            echo sprintf(">> Loading %d of %d movies...\n", $i*10, 13510);
            
            $response = $browser->get(sprintf('http://kinoland.pl/filmy_online/strona-%d.html', $i));
            $crawler = new Crawler;
            $crawler->addHtmlContent((string)$response);
            
            $movieArray = array();
            
            foreach($crawler->filter('.film-main') as $movie)
            {
                preg_match('/Kategorie: (?P<categories>.*)?/', $movie->getElementsByTagName('div')->item(1)->nodeValue, $tmpCategory);
                preg_match('/(?<title>.*?) \[(?<translation>.*?)\]/', $movie->getElementsByTagName('h2')->item(0)->nodeValue, $tmpData);
                preg_match('/film\_online\/(?P<href>\d+)/', $movie->getElementsByTagName('a')->item(0)->getAttribute('href'), $tmpDataHref);
                
                $movieEntity = new Movie;
                $movieEntity->setBody($movie->getElementsByTagName('div')->item(5)->nodeValue);
                $movieEntity->setPhoto($movie->getElementsByTagName('img')->item(0)->getAttribute('src'));
                $movieEntity->setRemoteKey($tmpDataHref['href']);
                $movieEntity->setTitle($tmpData['title']);
                $movieEntity->setTranslation($tmpData['translation']);
                $movieEntity->setRatingCount(0);
                $movieEntity->setRatingValue(5);
                
                foreach(explode(',', str_replace(', ', ',', $tmpCategory['categories'])) as $catItem)
                {
                    $categoryEntity = new Category;
                    $categoryEntity->setName(trim($catItem));
                    $categoryEntity->setType(2);
                    
                    $movieEntity->getCategories()->add($categoryEntity);
                    
                    $em->persist($categoryEntity);
                }
                
                try 
                {
                    $em->persist($movieEntity);
                    $em->flush();
                }catch(Exception $e)
                {
                    echo $e->getMessage();
                }
            }
        }
    }
}

