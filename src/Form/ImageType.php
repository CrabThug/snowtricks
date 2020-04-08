<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alt', TextType::class, [
                'attr' => [
                    'class' => "w3-padding",
                    'placeholder' => "Alternatif name",
                ],
                'label' => FALSE,
                'error_bubbling' => TRUE,
            ])
            ->add('bool', CheckboxType::class, [
                'label' => 'Main picture',
                'error_bubbling' => TRUE,
                'attr' => [
                    'onclick' => "check(this)",
                    'style' => "margin: 0 8px 16px 16px ;",
                    'class' => "w3-check",
                    'type' => "checkbox",
                ],
                'required' => FALSE
            ])
            ->add('file', FileType::class, [
                'label' => FALSE,
                'required' => FALSE,
                'error_bubbling' => TRUE,
                'attr' => [
                    'style' => "w3-center w3-blue w3-button",
                    'onchange' => 'mainShow(this)'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '4096M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/pjpeg',
                            'image/png',
                            'image/x-png'
                        ],
                        'mimeTypesMessage' => 'Le fichier transmit ne semble pas etre une image',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
