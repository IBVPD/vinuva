<?php

namespace App\Form\Types;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Models\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HospitalType extends AbstractType
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

        $hospitals = $user instanceof User ? $user->getHospitals() : new ArrayCollection();

        $resolver->setDefaults([
            'class' => Hospital::class,
            'query_builder' => static function (EntityRepository $repository) use ($user, $hospitals) {
                if ($user instanceof User) {
                    if (count($hospitals) === 1) {
                        return $repository->createQueryBuilder('h')->where('h.id = :hId AND h.active = true')->setParameter('hId', $hospitals->first()->getId());
                    }

                    if (count($hospitals) > 1) {
                        $ids       = [];
                        foreach ($hospitals as $hospital) {
                            $ids[] = $hospital->getId();
                        }

                        return $repository->createQueryBuilder('h')->where('h.id IN (:ids) AND h.active = true')->setParameter('ids', $ids)->orderBy('h.name');
                    }

                    if ($user->getCountry()) {
                        return $repository->createQueryBuilder('h')->where('h.country = :country AND h.active = true')->setParameter('country', $user->getCountry())->orderBy('h.name');
                    }
                }

                return $repository->createQueryBuilder('h')->orderBy('h.name');
            },
            'choice_attr' => static function ($choiceValue) {
                return ['data-country' => $choiceValue->getCountry()->getId()];
            },
        ]);

        if (count($hospitals) === 1) {
            $resolver->setDefault('placeholder', false);
            $resolver->setDefault('data', $hospitals->first());
        }
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
