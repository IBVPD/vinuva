<?php

namespace App\Controller;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Form\Types\ForgotPasswordType;
use App\Form\Types\LoginType;
use App\Form\Types\ResetPasswordType;
use App\Repository\UserRepository;
use NS\TokenBundle\Generator\InvalidTokenException;
use NS\TokenBundle\Generator\TokenGenerator;
use Paho\Vinuva\Models\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController
{
    use FormFactoryControllerTrait;
    use GeneralControllerTrait;
    use TwigRenderingTrait;

    public function loginAction(AuthenticationUtils $helper): Response
    {
        $loginForm = $this->formFactory->create(LoginType::class, null, ['method' => 'POST', 'action' => $this->router->generate('login_check')]);

        return $this->render('@App/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
            'form' => $loginForm->createView(),
            'with_login' => true,
        ]);
    }

    public function forgotAction(Request $request, UserRepository $repository, TokenGenerator $generator, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        $loginForm = $this->formFactory->create(ForgotPasswordType::class, null, ['method' => 'POST', 'action' => $this->router->generate('forgotPassword')]);
        $loginForm->handleRequest($request);
        if ($loginForm->isSubmitted() && $loginForm->isValid()) {
            $this->flash->addSuccess('Success', 'If an account with this email address exists you\'ll receive a link to reset the password');

            $user = $repository->findByLogin($loginForm['_username']->getData());
            if ($user) {
                $generator->setExpiration(86400);
                $token = $generator->getToken($user->getId(), $user->getEmail());
                $route = $this->router->generate('resetPassword', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                $email = (new TemplatedEmail())
                    ->from(new Address('noreply@vinuvacasos.org', 'Vinuva Aggregado'))
                    ->to($user->getEmail())
                    ->subject($translator->trans('Password Reset Request'))
                    ->htmlTemplate('Mail/forgot-password.html.twig')
                    ->context(['user' => $user, 'route' => $route]);

                $mailer->send($email);
            }

            return new RedirectResponse($this->router->generate('login'));
        }

        return $this->render('@App/forgot-password.html.twig', [
            'form' => $loginForm->createView(),
        ]);
    }

    public function resetPasswordAction(TokenGenerator $generator, EncoderFactoryInterface $factory, Request $request, string $token): Response
    {
        try {
            $result = $generator->decryptToken($token);
        } catch (InvalidTokenException $exception) {
            $this->flash->addError('Error', 'The url is invalid or expired');
            return new RedirectResponse($this->router->generate('login'));
        }

        $user = $this->entityManager->find(User::class, $result->getId());
        if (!$user) {
            $this->flash->addError('Error', 'The url is invalid or expired');
            return new RedirectResponse($this->router->generate('login'));
        }

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $factory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($user->getPlainPassword(), $user->getSalt()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->flash->addSuccess('Success', 'Your password has been reset');
            return new RedirectResponse($this->router->generate('login'));
        }

        return $this->render('@App/reset-password.html.twig', [
            'token' => $token,
            'form' => $form->createView(),
        ]);
    }
}
