<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'First Name*'])
            ->add('lastName', TextType::class, ['label' => 'Last Name*'])
            ->add('email', EmailType::class, ['label' => 'Email*'])
            ->add('telephone', TelType::class, ['label' => 'Telephone Number', 'required' => false])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The Passwords Do Not Match',
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                'constraints' => [
                    new NotBlank(['message' => 'This field is required']),
                    new Length([
                        'min' => 5,
                        'max' => 4096,
                    ])
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
