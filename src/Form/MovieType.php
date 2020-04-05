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
            ->add('alt', TextType::class, [
                'attr' => [
                    'style' => "margin-bottom : 8px",
                    'class' => "w3-padding",
                    'placeholder' => "Alternatif name",
                ],
                'error_bubbling' => TRUE,
                'label' => FALSE,
            ])
            ->add('embed', TextareaType::class, [
                'label' => FALSE,
                'attr' => [
                    'placeholder' => "Embed Video",
                    'class' => "w3-border w3-padding",
                    'style' => "min-width:382;max-width:382;width:382;height:58px;min-height:58px",
                ],
                'error_bubbling' => TRUE,
                'constraints' => [
                    new Url()
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
