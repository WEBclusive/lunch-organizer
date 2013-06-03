<?php

namespace Webclusive\Bundle\LunchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('hipchatHandle')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webclusive\Bundle\LunchBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'webclusive_bundle_lunchbundle_usertype';
    }
}
