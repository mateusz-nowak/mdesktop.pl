<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Acme\MainBundle\Form\Type\CommentType;
use Acme\MainBundle\Entity\Comment;
use Acme\MainBundle\Entity\Movie;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Track controller.
 *
 * @Route("/movie")
 */
class MovieController extends Controller
{
    /**
     * Show movie entity
     *
     * @Route("/{slug}/show", name="movie_show")
     * @Template()
     */
    public function showAction($slug)
    {
        $movie = $this->getDoctrine()->getRepository('AcmeMainBundle:Movie')->findOneBySlug($slug);

        if (!$movie) {
            throw $this->createNotFoundException('Unable to find movie entity.');
        }

        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->get('menu_builder_service');
        $navigation
            ->addLocation('Kategorie filmowe', array('route' => 'category'))
            ->addLocation($movie->getTitle(), array(
                'route' => 'movie_show',
                'routeParameters' => array(
                    'slug' => $movie->getSlug()
                )
            ));

        return array
        (
            // 'artist' => current($lastApiTrackClient->search(current(explode(' ', $track->getTitle())))),
            'movie' => $movie,
            'form' => $this->createForm(new CommentType, new Comment)->createView(),
        );
    }
    /**
     * Comment Movie.
     *
     * @Route("/{slug}/comment", name="movie_comment")
     * @Method({"POST"})
     */
    public function commentAction($slug)
    {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $movie = $this->getDoctrine()->getRepository('AcmeMainBundle:Movie')->findOneBySlug($slug);

        if (!$movie) {
            throw $this->createNotFoundException('Unable to find track entity.');
        }

        $comment = new Comment;
        $form = $this->createForm(new CommentType, $comment);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $comment->setUser($this->getUser());
                $movie->addComment($comment);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($movie);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Dziękujemy za dodanie komentarza');
            } else {
                $this->get('session')->setFlash('error', 'Nie udało się dodać kometnarza');
            }
        }

        return $this->redirect($this->generateUrl('movie_show', array('slug' => $movie->getSlug())));
    }

    /**
     * Vote Movie.
     *
     * @Route("/{id}/vote/{vote}", name="movie_vote")
     */
    public function voteAction(Movie $movie, $vote)
    {
        $sessionName = 'movieVoted';
        $em = $this->getDoctrine()->getEntityManager();

        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        if ($vote > 10 || $vote < 0) {
            throw new Exception('Vote has to be between 1 and 10');
        }

        $votedIds = (array) $this->get('session')->get($sessionName);

        if (in_array($movie->getId(), $votedIds)) {
            $this->get('session')->setFlash('error', 'Już głosowałeś na ten film');

            return $this->redirect($this->generateUrl('movie_show', array('slug' => $movie->getSlug())));
        }

        $votedIds[] = $movie->getId();
        $this->get('session')->set($sessionName, $votedIds);

        $movie->setRatingValue(($movie->getRatingCount()*$movie->getRatingValue()+$vote)/($movie->getRatingCount()+1));
        $movie->setRatingCount($movie->getRatingCount()+1);

        $em->merge($movie);
        $em->flush();

        $this->get('session')->setFlash('notice', 'Dziękujemy za oddanie głosu');

        return $this->redirect($this->generateUrl('movie_show', array('slug' => $movie->getSlug())));
    }
}
