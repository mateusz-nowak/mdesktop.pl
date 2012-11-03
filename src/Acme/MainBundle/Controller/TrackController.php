<?php

namespace Acme\MainBundle\Controller;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Acme\MainBundle\Form\Type\CommentType;
use Acme\MainBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
     * @Route("", name="track")
     * @Template()
     */
    public function indexAction()
    {
        $paginator = $this->get('knp_paginator');
		
        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->get('menu_builder_service');
        $navigation
            ->addLocation('Utwory muzyczne', array('route' => 'track'));

        return array
        (
        	'played' => $paginator->paginate(
                $this->getDoctrine()->getEntityManager()->getRepository('AcmeMainBundle:Played')->findBy(array(
                	'type' => 1
            	)),
                $this->get('request')->query->get('page', 1)
            ),
        );
    }

    /**
     * Show Track entity.
     *
     * @Route("/{slug}/show", name="track_show")
     * @Template()
     */
    public function showAction($slug)
    {
        $track = $this->getDoctrine()->getRepository('AcmeMainBundle:Track')->find((int) $slug);

        if (!$track) {
            throw $this->createNotFoundException('Unable to find track entity.');
        }

        if (!$track->getSize()) {
            try {
                $trackMetaData = $this
                    ->get('track_container_service')
                    ->getTrackMetaData($track->getRemote());

                $track->setSize($trackMetaData->size);

                $this->getDoctrine()->getEntityManager()->persist($track);
                $this->getDoctrine()->getEntityManager()->flush($track);
            } catch (RuntimeException $runtimeException) {
                $this->get('session')->setFlash('error', 'Ten plik został usunięty');

                return $this->redirect($this->get('request')->server->get('HTTP_REFERER'));
            }
        }

        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->get('menu_builder_service');
        $navigation
            ->addLocation('Utwory muzyczne', array('route' => 'track'))
            ->addLocation($track->getTitle(), array(
                'route' => 'track_show',
                'routeParameters' => array
                (
                    'slug' => $track->getSlug(),
                ),
            ));

        // Last.FM Api Integration
        $lastApiTrackClient = $this->get('binary_thinking_lastfm.client.artist');

        return array
        (
            'artist' => current($lastApiTrackClient->search(current(explode(' ', $track->getTitle())))),
            'track' => $track,
            'form' => $this->createForm(new CommentType, new Comment)->createView(),
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

        $page = $this->getRequest()->query->get('page', 1);

        $trackContainerService->setParameters(array(
            'page' => $page,
            'query' => ($query = $this->getRequest()->query->get('q')),
        ));

        if (strlen($query) < 3) {
            $this->get('session')->setFlash('error', 'Niepoprawne zapytanie');

            return $this->redirect($this->generateUrl('root'));
        }

        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->get('menu_builder_service');
        $navigation
            ->addLocation('Utwory muzyczne', array('route' => 'track'))
            ->addLocation($this->get('request')->query->get('q'), array(
                'route' => 'track_search',
                'routeParameters' => array
                (
                    'q' => $this->get('request')->query->get('q'),
                ),
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
     * @Route("/{slug}/download", name="track_download")
     */
    public function downloadAction($slug)
    {
        /** @var $trackContainerService \Acme\MainBundle\Model\TrackGrabberInterface */
        $trackContainerService = $this->get('track_container_service');

        $track = $this->getDoctrine()->getRepository('AcmeMainBundle:Track')->find((int) $slug);

        if (!$track) {
            throw $this->createNotFoundException('Unable to find track entity.');
        }

        return $trackContainerService->processDownload($track);
    }

    /**
     * Comment Track.
     *
     * @Route("/{slug}/comment", name="track_comment")
     * @Method({"POST"})
     */
    public function commentAction($slug)
    {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $track = $this->getDoctrine()->getRepository('AcmeMainBundle:Track')->find((int) $slug);

        if (!$track) {
            throw $this->createNotFoundException('Unable to find track entity.');
        }

        $comment = new Comment;
        $form = $this->createForm(new CommentType, $comment);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $comment->setUser($this->getUser());
                $track->addComment($comment);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($track);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Dziękujemy za dodanie komentarza');
            } else {
                $this->get('session')->setFlash('error', 'Nie udało się dodać kometnarza');
            }
        }

        return $this->redirect($this->generateUrl('track_show', array('slug' => $track->getSlug())));
    }
}
