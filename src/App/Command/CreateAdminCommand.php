<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Paho\Vinuva\Models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class CreateAdminCommand extends Command
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    public function __construct(EntityManagerInterface $entityManager, EncoderFactoryInterface $encoderFactory)
    {
        parent::__construct('vinuva:admin:create');

        $this->entityManager  = $entityManager;
        $this->encoderFactory = $encoderFactory;
    }

    protected function configure(): void
    {
        $this->setDefinition([
            new InputArgument('name', InputArgument::REQUIRED),
            new InputArgument('login', InputArgument::REQUIRED),
            new InputArgument('email', InputArgument::REQUIRED),
            new InputArgument('password', InputArgument::REQUIRED),
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $user    = User::createAdmin($input->getArgument('name'), $input->getArgument('login'), $input->getArgument('email'));
        $encoder = $this->encoderFactory->getEncoder($user);
        $user->setPassword($encoder->encodePassword($input->getArgument('password'), $user->getSalt()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $output->writeln('Added');

        return 0;
    }
}
