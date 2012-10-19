<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CommentAdmin extends Admin
{
    public function configureShowFields(ShowMapper $filter)
    {
        $filter
            ->add('text', null, array('label' => 'comment.text'))
            ->add('user', null, array('label' => 'comment.user'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('text', null, array('label' => 'comment.text'))
            ->add('user', null, array('label' => 'comment.user'))
            ->add('_action', 'actions', array(
                'label' => 'actions',
                'actions' => array(
                    'delete' => array(),
                )
            ));

        return $listMapper;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('text', null, array('label' => 'comment.text', 'attr' => array(
                'class' => 'tinymce',
                'data-theme' => 'medium',
                'style' => join(';', array(
                    'width: 800px',
                    'height: 300px',
                ))
            )))
            ->add('user', null, array('label' => 'comment.user'));

    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('show');
    }
}
