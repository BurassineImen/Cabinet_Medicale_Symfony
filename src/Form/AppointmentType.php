<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('doctor', EntityType::class, [
            'class' => Doctor::class,
            'choice_label' => 'name',
            'label' => 'Choisir un médecin',
        ])
        ->add('date', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Choisir une date et une heure',
        ]);
     /*    ->add('patientName', TextType::class, [
            'label' => 'Nom du patient',
            'mapped' => false, // Ce champ n'est pas directement mappé à l'entité
        ])
        ->add('patientEmail', TextType::class, [
            'label' => 'Email du patient',
            'mapped' => false, // Ce champ n'est pas directement mappé à l'entité
        ]);
         */
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
