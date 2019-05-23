<?php

namespace App\Controller;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Controller\Traits\UserProviderTrait;
use App\Form\UserProfileType;
use NS\FlashBundle\Service\Messages;
use Paho\Vinuva\Models\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserController
{
    use FormFactoryControllerTrait;
    use GeneralControllerTrait;
    use TwigRenderingTrait;
    use UserProviderTrait;

    /**
     * @Route("/profile", name="userProfile")
     * @param EncoderFactoryInterface $encoderFactory
     * @param Messages                $flash
     * @param Request                 $request
     *
     * @return Response
     */
    public function profileAction(EncoderFactoryInterface $encoderFactory, Messages $flash, Request $request): Response
    {
        $form = $this->createForm(UserProfileType::class, $this->getUser());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $plainPassword = $user->getPlainPassword();
                if ($plainPassword !== null) {
                    $encoder = $encoderFactory->getEncoder($user);
                    $user->setPassword($encoder->encodePassword($plainPassword, $user->getSalt()));
                }

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $flash->addSuccess('Success', 'Profile Updated');
                return new RedirectResponse($this->router->generate('userProfile'));
            }

            $flash->addWarning('Warning', 'Unable to update profile.');
        }

        return $this->render('@App/User/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
