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

        $topTrackPlaylist = array(
            'http://www.polskastacja.pl/radiochannel/HOT+100+-+Goraca+Setka+Nowosci..htm',
            'http://www.polskastacja.pl/radiochannel/Super+Przeboje.htm',
            'http://www.polskastacja.pl/radiochannel/Dance+100.htm',
            'http://www.polskastacja.pl/radiochannel/RnB.htm',
            'http://www.polskastacja.pl/radiochannel/HIP+HOP.htm',
            'http://www.polskastacja.pl/radiochannel/Polski+Hip+Hop.htm',
        );

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
