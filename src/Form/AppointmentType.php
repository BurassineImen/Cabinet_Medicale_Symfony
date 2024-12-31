<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('doctor', EntityType::class, [
                'class' => Doctor::class, // L'entité cible
                'choice_label' => 'name', // La propriété affichée dans la liste déroulante
                'label' => 'Choisir un médecin',
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text', // Rend le champ compatible avec HTML5
                'label' => 'Choisir une date et une heure',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
