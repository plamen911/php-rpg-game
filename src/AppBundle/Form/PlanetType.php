<?php

namespace AppBundle\Form;

use AppBundle\Entity\Player;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('x', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('y', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('player', EntityType::class, ['class' => Player::class]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array('data_class', 'AppBundle\Entity\Planet')
        );
    }

    public function getName()
    {
        return 'app_bundle_planet_type';
    }
}
