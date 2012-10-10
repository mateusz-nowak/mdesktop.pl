<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class MovieAdmin extends Admin
{

    public function configureShowFields(ShowMapper $filter)
    {
        $filter->add('title', null, array('label' => 'sonata.movie.title'))
               ->add('categories', null, array('label' => 'sonata.movie.category'))
               ->add('body', null, array('label' => 'sonata.movie.body'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title', null, array('label' => 'sonata.movie.title'))
                   ->add('categories', null, array('label' => 'sonata.movie.category'))
                   ->add('_action', 'actions', array(
                       'actions' => array(
                           'view' => array(),
                           'delete' => array(),
                       )
                   ));

        return $listMapper;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('edit');
        $collection->remove('create');
    }
}
