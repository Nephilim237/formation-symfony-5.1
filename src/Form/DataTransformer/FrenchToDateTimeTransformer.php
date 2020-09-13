<?php


namespace App\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{

    /**
     * @inheritDoc
     */
    public function transform($date)
    {
        if ($date === null) return '';

        return $date->format('d/m/Y');
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($frenchDate)
    {
        if ($frenchDate === null) throw new TransformationFailedException('Veuillez renseigner une date');

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        if ($date === false) throw new TransformationFailedException('Le format de date fourni est incorrect');

        return $date;
    }
}