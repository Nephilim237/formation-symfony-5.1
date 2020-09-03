<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * @param string $label
     * @param string|null $attrElt
     * @param array $options
     * @return array
     */
    protected function getAttributes(string $label, ?string $attrElt = '...', array $options = []): array
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $attrElt
            ],
        ], $options);
    }

}