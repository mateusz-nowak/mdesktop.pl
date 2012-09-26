<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class TrackAdmin extends Admin
{

    public function configureShowFields(ShowMapper $filter)
    {
        $filter->add('title', null, array('label' => 'sonata.track.title'))
               ->add('length', null, array('label' => 'sonata.track.length'))
               ->add('url', null, array('label' => 'sonata.track.url'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title', null, array('label' => 'sonata.track.title'))
                   ->add('length', null, array('label' => 'sonata.track.length'))
                   ->add('url', null, array('label' => 'sonata.track.url'))
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
