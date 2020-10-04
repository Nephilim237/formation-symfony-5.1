<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'rating',
                IntegerType::class,
                $this->getAttributes('Note', 'Donnez une note entre 0 et 5',['attr' => ['min' => 0, 'max' => 5, 'step' =>1 ] ]))
            ->add(
                'content',
                TextareaType::class,
                $this->getAttributes('Commentaire ou avis', 'Rédigez un avis avec autant de details que possible'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
