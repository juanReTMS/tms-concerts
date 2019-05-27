<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('institution', TextType::class, ['label' => 'Institution*'])
            ->add('building', TextType::class, ['label' => 'Building*'])
            ->add('floor', TextType::class, ['label' => 'Floor', 'required' => false])
            ->add('room', TextType::class, ['label' => 'Room', 'required' => false])
            ->add('seats', IntegerType::class, ['label' => 'Seats*',
                'attr' => ['min' => 0, 'max' => 2048]
            ])
            ->add('description', TextareaType::class, ['label' => 'Description*'])
            ->add('contacts');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
