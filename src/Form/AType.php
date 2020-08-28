<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getAttributes('Titre', 'Titre de l\'annonce')
            )
            ->add(
                'slug',
                TextType::class,
                $this->getAttributes('Slug', 'Optionnel', ['required' => false])
            )
            ->add(
                'coverImage',
                UrlType::class,
                $this->getAttributes('lien vers l\'image')
            )
            ->add(
                'introduction',
                TextType::class,
                $this->getAttributes('Intro', 'Petite introduction')
            )
            ->add(
                'content',
                TextareaType::class,
                $this->getAttributes('Description', 'Plus de details sur votre offre')
            )
            ->add(
                'rooms',
                IntegerType::class,
                $this->getAttributes('Nombre de chambre', 'Exemple: 04')
            )
            ->add(
                'price',
                MoneyType::class,
                $this->getAttributes('Prix par nuit', 'Montant en Fcfa')
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }

    /**
     * @param string $label
     * @param string|null $attrElt
     * @param array $options
     * @return array
     */
    private function getAttributes(string $label, ?string $attrElt = '...', array $options = []): array
    {
        return array_merge([
          'label' => $label,
            'attr' => [
              'placeholder' => $attrElt
          ],
        ], $options);
    }
}
