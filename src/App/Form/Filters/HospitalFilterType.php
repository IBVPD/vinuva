<?php

namespace App\Form\Filters;

use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;
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
            'class'         => Hospital::class,
            'attr'          => ['size' => 15],
            'choice_attr'   => static function ($choiceValue) {
                return ['data-country' => $choiceValue->getCountry()->getId()];
            },
            'query_builder' => static function (EntityRepository $repository) use ($user, $hospitals) {
                if ($user instanceof User) {
                    if (count($hospitals) === 1) {
                        return $repository->createQueryBuilder('h')
                            ->addSelect('c')
                            ->innerJoin('h.country', 'c')
                            ->where('h.id = :hId')
                            ->setParameter('hId', $hospitals->first()->getId());
                    }

                    if (count($hospitals) > 1) {
                        $ids = [];
                        foreach ($hospitals as $hospital) {
                            $ids[] = $hospital->getId();
                        }

                        return $repository->createQueryBuilder('h')
                            ->addSelect('c')
                            ->innerJoin('h.country', 'c')
                            ->where('h.id IN (:ids)')
                            ->setParameter('ids', $ids)
                            ->orderBy('c.name,h.name');
                    }

                    if ($user->getCountry()) {
                        return $repository->createQueryBuilder('h')
                            ->addSelect('c')
                            ->innerJoin('h.country', 'c')
                            ->where('h.country = :country')
                            ->setParameter('country', $user->getCountry())
                            ->orderBy('c.name,h.name');
                    }
                }

                return $repository->createQueryBuilder('h')
                    ->addSelect('c')
                    ->innerJoin('h.country', 'c')
                    ->orderBy('c.name,h.name');
            },
            'group_by'      => static function (?Hospital $choice, $key, $value) {
                if ($choice) {
                    return $choice->getCountry()->getName();
                }
            },
            'multiple'      => true,
            'apply_filter'  => static function (ORMQuery $filterQuery, $field, $values) {
                if (!empty($values['value'])) {
                    $qb = $filterQuery->getQueryBuilder();
                    $qb->andWhere($values['alias'] . '.hospital IN (:filterHospital)')->setParameter('filterHospital', $values['value']);
                }
            },
        ]);

        if ($hospitals) {
            $resolver->setDefault('data', $hospitals);
        }
    }

    public function getParent(): string
    {
        return EntityFilterType::class;
    }
}
