<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ShoutboxAdmin extends Admin
{

    public function configureShowFields(ShowMapper $filter)
    {
        $filter->add('text', null, array('label' => 'sonata.shoutbox.text'))
               ->add('user', null, array('label' => 'sonata.shoutbox.user'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('text', null, array('label' => 'sonata.shoutbox.text'))
                   ->add('user', null, array('label' => 'sonata.shoutbox.user'));

        return $listMapper;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('text', null, array('label' => 'sonata.shoutbox.text'))
             ->add('user', null, array('label' => 'sonata.shoutbox.user'));

    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('show');
    }

}
