<?php

namespace App\Form;

use App\Entity\Questiontest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class QuestiontestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation')
            ->add('reponseCorrecte',TextareaType::class)
            ->add('reponseFausse1',TextareaType::class)
            ->add('reponseFausse2',TextareaType::class)
            ->add('reponseFausse3',TextareaType::class)
            ->add('note')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questiontest::class,
        ]);
    }
}
