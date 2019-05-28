<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'First Name*'])
            ->add('lastName', TextType::class, ['label' => 'Last Name*'])
            ->add('email', EmailType::class, ['label' => 'Email*'])
            ->add('telephone', TelType::class, ['label' => 'Telephone Number', 'required' => false])
            ->add('roles', ChoiceType::class,
                ['choices' => ['ROLE_ADMIN' => 'ROLE_ADMIN'], 'multiple' => true, 'required' => false])
            ->add('plainPassword', PasswordType::class)
            ->add('locations', null, ['required' => false])
            ->add('concerts', null, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
