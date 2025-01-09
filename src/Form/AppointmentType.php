<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Speciality;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('speciality', EntityType::class, [
                'class' => Speciality::class,
                'choice_label' => 'title',
                'label' => 'Choisir une spécialité',
                'placeholder' => 'Sélectionnez une spécialité',
                'mapped' => false, // Non mappé directement à l'entité Appointment
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Choisir une date et une heure',
            ])
            ->add('doctor', EntityType::class, [
                'class' => Doctor::class,
                'choice_label' => 'name',
                'label' => 'Choisir un médecin',
                'placeholder' => 'Veuillez sélectionner une spécialité d\'abord',
                'choices' => [], // Initialement vide, sera mis à jour dynamiquement
            ]);

        // Ajouter un écouteur pour mettre à jour le champ `doctor` dynamiquement
        $builder->get('speciality')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $speciality = $form->getData();

                if ($speciality) {
                    $doctors = $speciality->getDoctors();

                    $form->getParent()->add('doctor', EntityType::class, [
                        'class' => Doctor::class,
                        'choices' => $doctors,
                        'choice_label' => function (Doctor $doctor) {
                            return $doctor->getLastName() . ' ' . $doctor->getFirstName();
                        },
                        'label' => 'Choisir un médecin',
                        'placeholder' => 'Sélectionnez un médecin',
                    ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
