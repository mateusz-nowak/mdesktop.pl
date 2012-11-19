<?php

namespace Acme\MainBundle\Command;

use RuntimeException;
use DOMElement;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Acme\MainBundle\Entity\Content;
use Acme\MainBundle\Entity\Photo;
use Buzz\Browser;

class NewsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('main:news')
            ->setDescription('Search for new news');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
		
		$client = new Browser;
		$response = (string) $client->get('http://www.pomponik.pl/');
		
		$crawler = new Crawler;
		$crawler->addHtmlContent($response, 'UTF-8');

		$newsContainer = array();
		foreach ($crawler->filter('.boxBody .list ul li') as $news) {
			if (!$news->getElementsByTagName('h3')->item(0) instanceof DOMElement) {
				continue;
			}
			
			$title = trim($news->getElementsByTagName('h3')->item(0)->nodeValue);
			$text = $this->getContent('http://www.pomponik.pl' . $news->getElementsByTagName('a')->item(1)->getAttribute('href'));
			
			if ($em->getRepository('AcmeMainBundle:Content')->findBy(array('title' => $title))) {
				continue;
			}
			
			if (strlen($text) < 500) {
				continue;
			}
			
			$newsContainer[] = array(
				'title' => $title,
				'photo' => $news->getElementsByTagName('img')->item(0)->getAttribute('src'),
				'text' => $text,
			);

		}
		
		$newsContainer = array_slice($newsContainer, 0, 15);

		foreach ($newsContainer as $newsItem) {
			$news = new Content;
			$news->setText($newsItem['text']);
			$news->setTitle($newsItem['title']);
			$news->addCategory($em->getRepository('AcmeMainBundle:Category')->findOneBy(array('slug' => 'wiadomosci')));
			$news->setThumbnail($newsItem['photo']);
			
			$em->persist($news);
		}
		
		$em->flush();
	}
	
	protected function getContent($url)
	{
		$client = new Browser;
		$response = (string) $client->get($url);
		
		$crawler = new Crawler;
		$crawler->addHtmlContent($response, 'UTF-8');
		
		$content = '';
		
		foreach ($crawler->filter('div.textContent')->eq(1)->filter('p') as $text) {
			$content .= '<p>' . trim($text->nodeValue) . '</p>';
		}
		
		return $content;
	}
}
