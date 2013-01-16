<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MainBundle\Entity\Content;
use Acme\MainBundle\Repository\Content as ContentRepository;
use Acme\MainBundle\Model\Gossip;

/**
 * Content controller.
 *
 * @Route("/news")
 */
class NewsController extends Controller
{
    /**
     * Finds and displays a Content entity.
     *
     * @Route("", name="news")
     * @Template()
     */
    public function indexAction()
    {
        $paginator = $this->get('knp_paginator');

        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->get('menu_builder_service');
        $navigation
            ->addLocation('Aktualności', array('route' => 'news'));

        return array(
            'news' => $paginator->paginate(
                $this->getDoctrine()->getEntityManager()->getRepository('AcmeMainBundle:Content')->findAllByCategoryName(ContentRepository::NEWS),
                $this->get('request')->query->get('page', 1),
                9
            ),
        );
    }

    /**
     * Display an RSS feed.
     *
     * @Template()
     */
    public function gossipAction()
    {
        return array(
            'gossips' => Gossip::toArray(),
        );
    }
}
