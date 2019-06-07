<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Paho\Vinuva\Models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AdminResetPasswordCommand extends Command
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    public function __construct(EntityManagerInterface $entityManager, EncoderFactoryInterface $encoderFactory)
    {
        parent::__construct('vinuva:admin:reset-password');

        $this->entityManager  = $entityManager;
        $this->encoderFactory = $encoderFactory;
    }

    protected function configure(): void
    {
        $this->setDefinition([
            new InputArgument('email', InputArgument::REQUIRED),
            new InputArgument('password', InputArgument::REQUIRED),
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $email = $input->getArgument('email');
        /** @var User|null $user */
        $user  = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $output->writeln('<error>Unable to retrieve user</error>');
            return null;
        }

        $encoder  = $this->encoderFactory->getEncoder($user);
        $password = $input->getArgument('password');
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $output->writeln(sprintf('Updated user %s with password %s', $email, $password));
    }
}
