<?php

namespace App\Form;

use App\Entity\Evenement;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditEvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('emplacement',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('prix',IntegerType::class,array('attr'=>array('class'=>'form-control')))
            ->add('dateEvent',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('imageFile',FileType::class,[
                'required'=>false
            ])
            ->add('fondation',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('nbmaxparticipants',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('duree',TextType::class,array('attr'=>array('class'=>'form-control')))
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptcha'
            ))
            ->add('Modifier',SubmitType::class,array(

                'attr' => array('class' => 'btn btn-success my-3')))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
