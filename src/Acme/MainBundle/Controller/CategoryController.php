<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Track controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("", name="category")
     * @Template()
     */
    public function indexAction()
    {
        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->get('menu_builder_service');
        $navigation
            ->addLocation('Kategorie filmowe', array('route' => 'category'));

        return array(
            'categories' => $this->get('doctrine.orm.entity_manager')->getRepository('AcmeMainBundle:Category')->findBy(array('type' => 2)),
        );
    }

    /**
     * Lists all Category entities.
     *
     * @Route("/{slug}/movies", name="category_movie")
     * @Template()
     */
    public function categoryAction($slug)
    {
        $paginator = $this->get('knp_paginator');

        $category = $this->get('doctrine.orm.entity_manager')->getRepository('AcmeMainBundle:Category')->findOneBy(array(
            'type' => 2, 'slug' => $slug,
        ));

        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->get('menu_builder_service');
        $navigation
            ->addLocation('Kategorie filmowe', array('route' => 'category'))
            ->addLocation($category->getName(), array(
                'route' => 'category_movie',
                'routeParameters' => array
                (
                    'slug' => $category->getSlug()
                )
            ));

        if ($this->get('request')->query->get('page') > 1) {
            $navigation->addLocation('Strona ' . $this->get('request')->query->get('page', 1));
        }

        if (!$category) {
            $this->createNotFoundException('Entity category does not found');
        }

        $movies = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AcmeMainBundle:Movie')
            ->createQueryBuilder('m')
            ->leftJoin('m.categories', 'c')
            ->where('c.slug = :slug')
            ->setParameter('slug', $category->getSlug());

        return array(
            'movies' => $paginator->paginate(
                $movies,
                $this->get('request')->query->get('page', 1)
            ),
            'category' => $category,
        );
    }
}
