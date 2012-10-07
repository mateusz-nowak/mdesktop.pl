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
     * Search Tracks entities.
     *
     * @Route("/search", name="track_search")
     * @Template()
     */
    public function searchAction()
    {
        /** @var $trackContainerService \Acme\MainBundle\Model\TrackGrabberInterface */
           $trackContainerService = $this->get('track_container_service');
        $trackContainerService->setParameters(array(
            'page' => 1,
            'query' => $this->getRequest()->query->get('q'),
        ));

        return array(
            'tracks' => $trackContainerService->process(),
        );
    }

    /**
     * Download Track.
     *
     * @Route("/{slug}/download", name="track_download")
     */
    public function downloadAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $track = $em->getRepository('AcmeMainBundle:Track')->findOneBySlug($slug);

        if (!$track) {
            throw $this->createNotFoundException('Unable to find Track entity.');
        }

        /** @var $trackContainerService \Acme\MainBundle\Model\TrackGrabberInterface */
        $trackContainerService = $this->get('track_container_service');

        return $trackContainerService->processDownload($track);
    }

    /**
     * Finds and displays a Track entity.
     *
     * @Route("/{slug}/show", name="track_show")
     * @Template()
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmeMainBundle:Track')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Track entity.');
        }

        return array(
            'entity' => $entity,
        );
    }

}
