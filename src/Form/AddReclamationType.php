<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujetReclamation', ChoiceType::class, array(
                'label'=>'',
                'choices' => [
                    'Technique' => 'Technique',
                    'Signaler un utilisateur' => 'Signaler un utilisateur',
                    'Paramètres du compte' => 'Paramètres du compte',
                    'Autres' => 'Autres',
                ]
            ))
            ->add('contenu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
