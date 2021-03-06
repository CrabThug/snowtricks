<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Choose a title',
                    'class' => "w3-padding w3-border",
                    'onkeyup' => "titleTag(this)",
                ],
                'label' => FALSE,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'break-word w3-border w3-padding',
                    'placeholder' => 'Write a description',
                ],
                'label' => FALSE,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'w3-select w3-border'],
                'label' => FALSE,
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => TRUE,
                'allow_delete' => TRUE,
                'label' => FALSE,
            ])
            ->add('movies', CollectionType::class, [
                'entry_type' => MovieType::class,
                'allow_add' => TRUE,
                'allow_delete' => TRUE,
                'label' => FALSE,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'w3-btn w3-blue w3-round',

                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
