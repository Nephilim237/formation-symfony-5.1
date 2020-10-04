<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * @param string $label
     * @param string|null $placeholder
     * @param array $options
     * @return array
     */
    protected function getAttributes(string $label, ?string $placeholder = '...', array $options = []): array
    {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ],
        ], $options);
    }

}