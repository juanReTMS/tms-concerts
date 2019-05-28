<?php

namespace App\Form;

use App\Entity\Concert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;

class ConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Title*'])
            ->add('start', DateTimeType::class, ['label' => 'Start*'])
            ->add('end', DateTimeType::class, ['label' => 'End*'])
            ->add('price', IntegerType::class,
                ['label' => 'Price', 'attr' => ['min' => 0, 'max' => 256]]
            )
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('program', TextareaType::class, ['label' => 'Program'])
            ->add('organizers')
            ->add('location');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Concert::class,
        ]);
    }
}
