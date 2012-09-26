<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ContentAdmin extends Admin
{

    public function configureShowFields(ShowMapper $filter)
    {
        $filter->add('title', null, array('label' => 'sonata.page.title'))
               ->add('text', null, array('label' => 'sonata.page.text'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title', null, array('label' => 'sonata.page.title'))
                   ->add('category', null, array('label' => 'sonata.category.name'))
                   ->add('_action', 'actions', array(
                       'actions' => array(
                           'view' => array(),
                           'delete' => array(),
                       )
                   ));

        return $listMapper;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', null, array('label' => 'sonata.page.title'))
                   ->add('text', null, array('label' => 'sonata.page.text'))
                   ->add('category', null, array('label' => 'sonata.category.name'));

        return $formMapper;
    }
}
