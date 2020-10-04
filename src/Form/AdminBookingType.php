<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => "Date d'arrivee",
                'widget' => 'single_text'
            ])
            ->add('endDate', DateType::class, [
                'label' => "Date de depart",
                'widget' => 'single_text'
            ])
            ->add('comments', TextareaType::class ,[
                'label' => "Commentaire"
            ])
            ->add('booker', EntityType::class, [
                'label' => 'Visiteur',
                'class' => User::class,
                'choice_label' => function($user){
                    return ucfirst($user->getFirstName()) . ' ' . strtoupper($user->getLastName());
                }
            ])
            ->add('ad', EntityType::class, [
                'label' => 'Annonce a reserver',
                'class' => Ad::class,
                'choice_label' => 'title'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
