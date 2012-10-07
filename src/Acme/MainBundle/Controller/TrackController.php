<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MainBundle\Entity\Track;

/**
 * Track controller.
 *
 * @Route("/track")
 */
class TrackController extends Controller
{
    /**
     * Lists all Track entities.
     *
     * @Route("/", name="track")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmeMainBundle:Track')->findAll();

        return array(
            'entities' => $entities,
        );
	}

    /**
     * Finds and displays a Track entity.
     *
     * @Route("/{id}/show", name="track_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeMainBundle:Track')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Track entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

}
