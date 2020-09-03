<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getAttributes('Prénom', 'Renseigner votre prénom'))
            ->add('lastName', TextType::class, $this->getAttributes('Nom', 'Renseigner votre nom'))
            ->add('email', EmailType::class, $this->getAttributes('Email', 'Renseigner votre adresse email'))
            ->add('picture', UrlType::class, $this->getAttributes('Photo de profil', 'Lien vers la photo de profil'))
            ->add('hash', PasswordType::class, $this->getAttributes('Mot de passe', 'Renseigner le mot de passe'))
            ->add('confirmHash', PasswordType::class, $this->getAttributes('Confirmer le mot de passe', 'Renseigner le mot de passe a nouveau'))
            ->add('introduction', TextType::class, $this->getAttributes('Introduction', 'Décrivez breve'))
            ->add('description', TextareaType::class,$this->getAttributes('A propos de vous', 'Description approfondie'))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
