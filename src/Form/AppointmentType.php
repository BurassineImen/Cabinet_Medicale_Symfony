<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import correct pour EntityType
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Patient;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text', // Pour afficher un sélecteur de date unique
                'label' => 'Date',
                'attr' => [
                    'class' => 'form-control', // Optionnel : pour appliquer des styles Bootstrap
                ],
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class, // Spécifie l'entité associée
                'choice_label' => 'firstName', // Champ utilisé pour afficher les choix (remplacez par le bon champ si nécessaire)
                'label' => 'Patient',
                'placeholder' => 'Choisir un patient', // Optionnel : ajoute un choix vide
                'attr' => [
                    'class' => 'form-select', // Optionnel : pour appliquer des styles Bootstrap
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class, // Associe ce formulaire à l'entité Appointment
        ]);
    }
}
