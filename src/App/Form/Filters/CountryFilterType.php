<?php

namespace App\Form\Filters;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Paho\Vinuva\Models\Country;
use Paho\Vinuva\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CountryFilterType extends AbstractType
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

        $country = $user instanceof User ? $user->getCountry() : null;

        $resolver->setDefaults([
            'class' => Country::class,
            'query_builder' => static function (EntityRepository $repository) use ($country) {
                if ($country instanceof Country) {
                    return $repository->createQueryBuilder('c')->where('c.id = :cId')->setParameter('cId', $country->getId())->orderBy('c.name');
                }

                return $repository->createQueryBuilder('c')->innerJoin('c.hospitals','h')->orderBy('c.name');
            },
            'apply_filter' => static function (QueryInterface $filterQuery, $field, $values) {
                if (!empty($values['value'])) {
                    /** @var QueryBuilder $qb */
                    $qb = $filterQuery->getQueryBuilder();
                    $qb->andWhere("{$values['alias']}.$field = :filterCountry")->setParameter('filterCountry', $values['value']);
                }
            },
        ]);

        if ($country) {
            $resolver->setDefault('data', $country);
            $resolver->setDefault('placeholder', false);
        }
    }

    public function getParent(): string
    {
        return EntityFilterType::class;
    }
}
