<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Speciality;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $role = $options['role'];
        $specialities = $options['specialities'];

        $builder
            ->add('email')
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('adress', TextType::class)
            ->add('phone', TextType::class)
            ->add('password', PasswordType::class);

        if ($role === 'medecin') {
            $builder->add('speciality', EntityType::class, [
                'class' => Speciality::class,
                'choices' => $specialities,
                'choice_label' => 'title',
                'required' => true,
            ]);
        }

        $builder->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'label' => 'I agree to the terms and conditions',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'role' => null,
            'specialities' => [],
        ]);
    }
}
