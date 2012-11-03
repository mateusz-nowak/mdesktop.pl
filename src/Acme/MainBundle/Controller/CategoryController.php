<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MainBundle\Form\Type\MovieFilterType;

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
     * @Template("AcmeMainBundle:Category:category.html.twig")
     */
    public function indexAction()
    {
    	$paginator = $this->get('knp_paginator');
		
		if($filter = $this->getRequest()->query->get('MovieFilter')) {
			$movies = $this->get('doctrine.orm.entity_manager')->getRepository('AcmeMainBundle:Movie')->filterQuery($filter);
		} else {
			$movies = $this->get('doctrine.orm.entity_manager')->getRepository('AcmeMainBundle:Movie')->findAll();
		}
		
        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->get('menu_builder_service');
        $navigation
            ->addLocation('Wszystkie filmy', array('route' => 'category'));

        return array(
            'movies' => $paginator->paginate(
                $movies,
                $this->get('request')->query->get('page', 1),
                9
            ),
            'form' => $this->createForm($this->get('movie_filter_type'))->createView(),
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
                $this->get('request')->query->get('page', 1),
                9
            ),
            'category' => $category,
        );
    }
}
