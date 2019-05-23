<?php

namespace App\Form\Types;

use Doctrine\ORM\EntityRepository;
use Paho\Vinuva\Models\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Region::class,
            'query_builder' => static function (EntityRepository $repository) {

                return $repository->createQueryBuilder('r')->orderBy('r.name');
            },
            'placeholder' => '',
        ]);
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
