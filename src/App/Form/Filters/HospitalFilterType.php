<?php

namespace App\Form\Filters;

use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HospitalFilterType extends AbstractType
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $user  = null;
        $token = $this->tokenStorage->getToken();
        if ($token) {
            $user = $token->getUser();
        }

        $hospitals = $user instanceof User ? $user->getHospitals() : [];

        $resolver->setDefaults([
            'class' => Hospital::class,
            'choice_attr' => static function ($choiceValue) {
                return ['data-country' => $choiceValue->getCountry()->getId()];
            },
            'query_builder' => static function (EntityRepository $repository) use ($user, $hospitals) {
                if ($user instanceof User) {
                    if (count($hospitals) === 1) {
                        return $repository->createQueryBuilder('h')->where('h.id = :hId')->setParameter('hId', $user->getHospitals()->first()->getId());
                    }

                    if (count($hospitals) > 1) {
                        $ids = [];
                        foreach ($hospitals as $hospital) {
                            $ids[] = $hospital->getId();
                        }

                        return $repository->createQueryBuilder('h')->where('h.id IN (:ids)')->setParameter('ids', $ids)->orderBy('h.name');
                    }

                    if ($user->getCountry()) {
                        return $repository->createQueryBuilder('h')->where('h.country = :country')->setParameter('country', $user->getCountry())->orderBy('h.name');
                    }
                }

                return $repository->createQueryBuilder('h')->orderBy('h.name');
            },
        ]);

        if ($hospitals) {
            $resolver->setDefault('data', $hospitals);
            $resolver->setDefault('placeholder', false);
        }
    }

    public function getParent(): string
    {
        return EntityFilterType::class;
    }
}
