<?php

namespace Acme\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Doctrine\ORM\EntityRepository;

class MovieFilterType extends AbstractType
{
    protected $em;

    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null, array(
            'required' => false,
            'label' => 'Nazwa filmu'
        ));
        $builder->add('translation', 'choice', array(
            'empty_value' => 'Bez znaczenia',
            'required' => false,
            'choices' => array(
                'Lektor', 'Czytany',
            ),
            'label' => 'Typ filmu',
        ));
        $builder->add('categories', 'entity', array(
            'label' => 'Kategoria',
            'class' => 'AcmeMainBundle:Category',
            'multiple' => true,
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('c')->where('c.type = 2');
            },
            'required' => false,
            'attr' => array(
                'class' => 'option_list'
            ),
        ));
    }

    public function getName()
    {
        return 'MovieFilter';
    }
}
