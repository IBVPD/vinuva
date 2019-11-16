<?php

namespace App\Form\Types;

use Doctrine\ORM\EntityRepository;
use Paho\Vinuva\Models\Country;
use Paho\Vinuva\Models\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CountryType extends AbstractType
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

        $country = $user instanceof User ? $user->getCountry():null;

        $resolver->setDefaults([
            'class' => Country::class,
            'placeholder' => ' ',
            'required' => false,
            'query_builder' => static function (EntityRepository $repository) use ($country) {
                if ($country) {
                    return $repository->createQueryBuilder('c')->where('c.id = :cId')->setParameter('cId', $country)->orderBy('c.name');
                }

                return $repository->createQueryBuilder('c')->orderBy('c.name');
            },
        ]);

        if ($country) {
            $resolver->setDefault('data', $country);
            $resolver->setDefault('placeholder', $user && $user->isAdmin() ? ' ': false);
        }
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
