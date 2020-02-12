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
                    'class' => "w3-margin-bottom"
                ],
                'label' => FALSE,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'break-word w3-input w3-border',
                    'placeholder' => 'Write a description',
                    'style' => 'min-height: 89px;height: 89px;max-width:600px;min-width:600px'
                ],
                'label' => FALSE,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'w3-select w3-margin-bottom w3-margin-top w3-col s2'],
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
                    'class' => 'w3-btn w3-blue w3-round w3-right',
                    'style' => 'margin-top: 50px'
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
