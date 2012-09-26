<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DesktopController extends Controller
{

    public function indexAction()
    {
        $categoryData = array();
        $buzzBrowser = new Browser;
        $response = $buzzBrowser->get('http://www.seans-online.eu/filmy.html');

        $crawler = new Crawler((string) $response);

        foreach ($crawler->filter('#left-box-cat a') as $linkItem) {
            preg_match('/(?P<name>.*?)\s\(/', $linkItem->nodeValue, $res);
            $categoryData[] = $res['name'];
        }

        die;
    }

}
