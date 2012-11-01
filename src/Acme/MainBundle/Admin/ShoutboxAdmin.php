<?php

namespace Acme\MainBundle\Admin;

use Acme\MainBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ShoutboxAdmin extends Admin
{
    public function configureShowFields(ShowMapper $filter)
    {
        $filter
            ->add('text', null, array('label' => 'shoutbox.text'))
            ->add('user', null, array('label' => 'shoutbox.user'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('text', null, array('label' => 'shoutbox.text'))
            ->add('user', null, array('label' => 'shoutbox.user'))
            ->add('_action', 'actions', array(
                'label' => 'actions',
                'actions' => array(
                    'view' => array(),
                    'delete' => array(),
                )
            ));

        return $listMapper;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('text', null, array('label' => 'shoutbox.text', 'attr' => array(
                'class' => 'tinymce',
                'data-theme' => 'medium',
                'style' => join(';', array(
                    'width: 800px',
                    'height: 300px',
                ))
            )))
            ->add('user', null, array('label' => 'shoutbox.user'));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('show');
    }
}
