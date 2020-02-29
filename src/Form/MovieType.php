<?php

namespace App\Form;

use App\Entity\Movie;
use App\Validator\Url;
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
            ->add('embed', TextType::class, [
                'label' => FALSE,
                'attr' => [
                    'placeholder' => "Embed Video",
                    'class' => "",
                    'style' => "min-width:250px",
                ],
                'constraints' => [
                    new Url()
                ],
            ])
            ->add('alt', TextType::class, [
                'attr' => [
                    'class' => "",
                    'placeholder' => "Nom Alternatif",
                ],
                'label' => FALSE,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
