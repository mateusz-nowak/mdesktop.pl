<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MovieAdmin extends Admin
{

    public function configureShowFields(ShowMapper $filter)
    {
        $filter->add('title', null, array('label' => 'sonata.track.title'))
               ->add('url', null, array('label' => 'sonata.track.url'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title', null, array('label' => 'sonata.track.title'))
                   ->add('url', null, array('label' => 'sonata.track.url'))
                   ->add('_action', 'actions', array(
                       'actions' => array(
                           'view' => array(),
                           'delete' => array(),
                       )
                   ));
                   
        return $listMapper;
    }
}