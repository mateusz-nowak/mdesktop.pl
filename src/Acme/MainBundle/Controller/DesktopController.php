<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DesktopController extends Controller
{

    public function indexAction()
    {
        $trackContainer = $this->get('track_container_service');
        $trackContainer->setParameters(array(
            'query' => 'peja - materialna dziwka',
            'page' => 2,
        ));

        var_dump($trackContainer->getResponse());die;
    }

}
