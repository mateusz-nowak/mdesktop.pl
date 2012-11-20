<?php

namespace Acme\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Acme\MainBundle\Entity\Played;
use Buzz\Browser;

class TrackCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('main:track')
            ->setDescription('Search for track hits');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $browser = new Browser;
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $response = (string) $browser->get('http://www.polskastacja.pl/');

        $crawler = new Crawler;
        $crawler->addHtmlContent($response);

        $topTrackPlaylist = array();
        foreach ($crawler->filter('#scrollkanal div div a') as $topTrackList) {
            $topTrackPlaylist[] = $topTrackList->getAttribute('href');
        }

        $topTrackPlaylist = array_unique(array_map(function($playlist) {
            return 'http://www.polskastacja.pl' . $playlist;
        }, array_filter($topTrackPlaylist, function($playlist) {
            return preg_match('/radiochannel/', $playlist);
        })));

        foreach ($topTrackPlaylist as $playlistUrl) {
            $response = (string) $browser->get($playlistUrl);

            $crawler = new Crawler;
            $crawler->addHtmlContent($response);

            $tmpData = array();
            foreach ($crawler->filter('.wykonawca .left a') as $playlistSong) {
                $tmpData[] = trim($playlistSong->nodeValue);
            }

            array_shift($tmpData);

            $played = $em->getRepository('AcmeMainBundle:Played')->findOneBy(array(
                'type' => 1,
                'header' => trim($crawler->filter('#name span')->text())
            ));

            if (!$played) {
                $played = new Played;
            }

            $played->setHeader(trim($crawler->filter('#name span')->text()));
            $played->setType(1);
            $played->setData($tmpData);

            if (!$played->getId()) {
                $em->persist($played);
            } else {
                $em->merge($played);
            }

            $em->flush();
        }
    }
}
