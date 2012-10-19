<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MainBundle\Entity\Content;
use Acme\MainBundle\Entity\Comment;
use Acme\MainBundle\Form\Type\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Content controller.
 *
 * @Route("/content")
 */
class ContentController extends Controller
{
    /**
     * Finds and displays a Content entity.
     *
     * @Route("/{slug}/show", name="content_show")
     * @Template()
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AcmeMainBundle:Content')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->get('menu_builder_service');
        $navigation
            ->addLocation($entity->getTitle(), array(
                'route' => 'content_show',
                'routeParameters' => array
                (
                    'slug' => $entity->getSlug()
                )
            ));

        return array
        (
            'content' => $entity,
            'form' => $this->createForm(new CommentType, new Comment)->createView(),
        );
    }

    /**
     * Comment Contents.
     *
     * @Route("/{slug}/comment", name="content_comment")
     * @Method({"POST"})
     */
    public function commentAction($slug)
    {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $content = $this->getDoctrine()->getRepository('AcmeMainBundle:Content')->findOneBySlug($slug);

        if (!$content || !$content->getCommentable()) {
            throw $this->createNotFoundException('Unable to find comment entity.');
        }

        $comment = new Comment;
        $form = $this->createForm(new CommentType, $comment);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $comment->setUser($this->getUser());
                $content->addComment($comment);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($content);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Dziękujemy za dodanie komentarza');
            } else {
                $this->get('session')->setFlash('error', 'Nie udało się dodać kometnarza');
            }
        }

        return $this->redirect($this->generateUrl('content_show', array('slug' => $content->getSlug())));
    }
}
