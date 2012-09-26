<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

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
                           // 'delete' => array(),
                       )
                   ));
                   
        return $listMapper;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', null, array('label' => 'sonata.category.name'));

        return $formMapper;
    }
}