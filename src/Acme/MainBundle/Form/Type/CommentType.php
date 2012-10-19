<?php

namespace Acme\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', null, array(
            'attr' => array
            (
                'class' => 'tinymce',
                'data-theme' => 'simple',
            )
        ));
    }

    public function getName()
    {
        return 'comment';
    }
}
