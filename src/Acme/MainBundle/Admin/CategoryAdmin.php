<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CategoryAdmin extends Admin
{

    public function configureShowFields(ShowMapper $filter)
    {
        $filter->add('name', null, array('label' => 'sonata.category.name'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', null, array('label' => 'sonata.category.name'))
                   ->add('_action', 'actions', array(
                       'actions' => array(
                           'view' => array(),
                       )
                   ));

        return $listMapper;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', null, array('label' => 'sonata.category.name'));

        return $formMapper;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('edit');
    }

}
