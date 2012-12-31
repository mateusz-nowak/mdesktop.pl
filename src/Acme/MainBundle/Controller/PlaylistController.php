<?php

namespace Acme\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\MainBundle\Entity\Playlist;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 *
 * @Route("/playlist")
 */
class PlaylistController extends Controller
{
    /**
     * Delete selected playlist
     *
     * @Route("/{id}/delete", name="playlist_delete")
     */
    public function deleteAction(Request $request, Playlist $playlist)
    {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $em
            ->getRepository('AcmeMainBundle:Playlist')
            ->createQueryBuilder('p')
            ->delete()
            ->where('p.name = :name')
            ->setParameter('name', $playlist->getName())
            ->getQuery()
            ->execute();

        $this->get('session')->setFlash('notice', 'Poprawnie usunięto playlistę');

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * Delete selected track from playlist
     *
     * @Route("/track/{id}/delete", name="playlist_delete_track")
     */
    public function deleteTrackAction(Request $request, Playlist $playlist)
    {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->get('doctrine.orm.entity_manager');

        $em->remove($playlist);
        $em->flush();

        $this->get('session')->setFlash('notice', 'Poprawnie usunięto utwór z playlisty');

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * Finds and displays a Playlist entity.
     *
     * @Template()
     */
    public function indexAction()
    {
        $tracks = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('AcmeMainBundle:Playlist')
            ->createQueryBuilder('p')
                ->groupBy('p.name')
                ->where('p.user = :user')
                ->setParameter('user', $this->getUser())
            ->getQuery()->execute();

        return array(
            'tracks' => $tracks
        );
    }

    /**
     * Comment Movie.
     *
     * @Route("/new", name="playlist_new")
     * @Method({"POST"})
     */
    public function newAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $user = $this->getUser();

        $playlist = new Playlist;

        if ($this->getRequest()->request->get('id')) {
            $playlist->setTrack($em->getRepository('AcmeMainBundle:Track')->find($this->getRequest()->request->get('id')));
        }

        $playlist->setUser($user);

        if ($this->getRequest()->request->get('name')) {
            $playlist->setName($this->getRequest()->request->get('name'));
        }

        $em->persist($playlist);
        $em->flush();

        return new Response($this->getRequest()->request->get('id'));
    }

    /**
     * Finds and displays a Playlist entity.
     *
     * @Route("/{id}/show", name="playlist_show")
     * @Template()
     */
    public function showAction(Playlist $playlist)
    {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $paginator = $this->get('knp_paginator');

        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->get('menu_builder_service');
        $navigation
            ->addLocation($playlist->getName(), array(
                'route' => 'playlist_show',
                'routeParameters' => array
                (
                    'id' => $playlist->getId()
                )
            ));

        $tracks = $this
            ->get('doctrine.orm.entity_manager')
            ->getRepository('AcmeMainBundle:Playlist')
            ->createQueryBuilder('p')
            ->where('p.user = :user')
            ->andWhere('p.track IS NOT NULL')
            ->setParameter('user', $this->getUser());

        if ($playlist->getDefaultName() !== NULL) {
            $tracks->andWhere('p.name = :name');
            $tracks->setParameter('name', $playlist->getName());
        } else {
            $tracks->andWhere('p.name is null');
        }

        return array(
            'tracks' => $paginator->paginate(
                $tracks->getQuery()->execute(),
                $this->get('request')->query->get('page', 1),
                9
            ),
        );
    }

}
