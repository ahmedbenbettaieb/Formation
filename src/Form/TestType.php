<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Test;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet')
            ->add('duree')
            ->add('idFormation',EntityType::class,['class' => Formation::class,
                                                              'choice_label' => 'titre',
                                                            'label' => 'Formation'
                                                         ]) ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Test::class,
        ]);
    }
}
