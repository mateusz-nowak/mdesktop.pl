<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PhotoAdmin extends Admin
{
    public function configureShowFields(ShowMapper $filter)
    {
        $filter
            ->add('file', null, array('label' => 'photo.file'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('file', null, array('label' => 'photo.file'))
            ->add('_action', 'actions', array(
                'label' => 'actions',
                'actions' => array(
                    'delete' => array(),
                )
            ));

        return $listMapper;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('file', null, array('label' => 'photo.file'));

        return $formMapper;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }
}
