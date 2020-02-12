<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('embed', TextareaType::class, [
                'label' => FALSE,
                'attr' => [  // todo Video form
                    'placeholder' => "Video",
                    'class' => "w3-input w3-border w3-margin",
                    'style' => "min-height: 43px;height: 43px;max-width:250px;min-width:250px"
                ],
            ])
            ->add('alt', TextType::class, [
                'attr' => [
                    'class' => "w3-margin"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
