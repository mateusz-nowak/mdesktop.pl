<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MainBundle\ObjectValue\Track;

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
        return array();
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
        
        $page = $this->getRequest()->query->get('page', 1);
        
        $trackContainerService->setParameters(array(
            'page' => $page,
            'query' => $this->getRequest()->query->get('q'),
        ));

        return array
        (
            'tracks' => $trackContainerService->process(),
            'next_page' => $trackContainerService->isNextPage(),
            'query' => $this->getRequest()->query->get('q'),
            'page' => ++$page
        );
    }

    /**
     * Download Track.
     *
     * @Route("/{id}/download", name="track_download")
     */
    public function downloadAction($id)
    {
        /** @var $trackContainerService \Acme\MainBundle\Model\TrackGrabberInterface */
        $trackContainerService = $this->get('track_container_service');
        
        $track = new Track;
        $track->setRemoteId($id);
        
        return $trackContainerService->processDownload($track);
    }

}
