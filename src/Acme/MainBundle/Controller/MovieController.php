<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Track controller.
 *
 * @Route("/movie")
 */
class MovieController extends Controller
{
    /**
     * Lists all Track entities.
     *
     * @Route("", name="movie")
     * @Template()
     */
    public function indexAction()
    {
    }

}
