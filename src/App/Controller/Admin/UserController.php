<?php

namespace App\Controller\Admin;

use App\Controller\Traits\FormFactoryControllerTrait;
use App\Controller\Traits\GeneralControllerTrait;
use App\Controller\Traits\TwigRenderingTrait;
use App\Form\Admin\User\CreateType;
use App\Form\Admin\User\EditType;
use App\Form\Admin\User\FilterType;
use App\Repository\UserRepository;
use NS\FilteredPaginationBundle\FilteredPagination;
use Paho\Vinuva\Models\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/admin/users")
 */
class UserController
{
    private const
        INDEX = 'admin.users';

    use GeneralControllerTrait;
    use TwigRenderingTrait;
    use FormFactoryControllerTrait;

    /**
     * @Route("/", name="adminUserIndex")
     *
     * @param FilteredPagination $pager
     * @param UserRepository     $repository
     * @param Request            $request
     *
     * @return Response
     */
    public function indexAction(FilteredPagination $pager, UserRepository $repository, Request $request): Response
    {
        $query  = $repository->getAllQB();
        $result = $pager->process($request, FilterType::class, $query, self::INDEX);

        if ($result->shouldRedirect()) {
            return new RedirectResponse($this->router->generate('adminUserIndex'));
        }

        $createForm = $this->createForm(CreateType::class);
        return $this->render('@App/Admin/User/index.html.twig', [
            'users' => $result->getPagination(),
            'createForm' => $createForm->createView(),
            'filterForm' => $result->getForm()->createView(),
        ]);
    }

    /**
     * @Route("/create", name="adminUserCreate", methods={"POST","GET"})
     * @param Request            $request
     *
     * @param ValidatorInterface $validator
     *
     * @return Response
     */
    public function createAction(Request $request, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();

                switch ($data['role']) {
                    case User::ROLE_ADMIN:
                        $user = User::createAdmin($data['name'], $data['login'], $data['email']);
                        break;
                    case User::ROLE_VERIFIER:
                        $user = User::createVerifier($data['name'], $data['login'], $data['email'], $data['country'], $data['hospitals']);
                        break;
                    case User::ROLE_COLLECTOR:
                        $user = User::createCollector($data['name'], $data['login'], $data['email'], $data['country'], $data['hospitals']);
                        break;
                    case User::ROLE_READER:
                        $user = User::createReader($data['name'], $data['login'], $data['email'], $data['country'], $data['hospitals']);
                        break;
                    default:
                        $this->flash->addError('Error', 'Unable to create user');
                        return new RedirectResponse($this->router->generate('adminUserIndex'));
                }

                $violations = $validator->validate($user);
                if (count($violations) === 0) {
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                    $this->flash->addSuccess('Success', 'User created successfully');
                    return new RedirectResponse($this->router->generate('adminUserIndex'));
                }

                /** @var ConstraintViolationInterface $violation */
                foreach($violations as $violation) {
                    $form[$violation->getPropertyPath()]->addError(new FormError($violation->getMessage()));
                }
            }

            $this->flash->addError('Error', 'Unable to create user');
        }

        return $this->render('@App/Admin/User/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{userId}/edit", name="adminUserEdit")
     * @param EncoderFactoryInterface $encoderFactory
     * @param Request                 $request
     * @param int                     $userId
     *
     * @return Response
     */
    public function editUser(EncoderFactoryInterface $encoderFactory, Request $request, int $userId): Response
    {
        /** @var User|null $user */
        $user = $this->entityManager->find(User::class, $userId);
        if (!$user) {
            throw new NotFoundHttpException('Unable to locate user');
        }

        $form = $this->createForm(EditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $plainPassword = $user->getPlainPassword();
                if ($plainPassword !== null) {
                    $encoder = $encoderFactory->getEncoder($user);
                    $user->setPassword($encoder->encodePassword($plainPassword, $user->getSalt()));
                }

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->flash->addSuccess('Success', 'User updated successfully');
                return new RedirectResponse($this->router->generate('adminUserIndex'));
            }

            $this->flash->addError('Error', 'Unable to update user');
        }

        return $this->render('@App/Admin/User/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
