<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MainBundle\Entity\Content;

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

        return array(
            'content' => $entity,
        );
    }
}