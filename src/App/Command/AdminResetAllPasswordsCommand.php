<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Paho\Vinuva\Models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AdminResetAllPasswordsCommand extends Command
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    public function __construct(EntityManagerInterface $entityManager, EncoderFactoryInterface $encoderFactory)
    {
        parent::__construct('vinuva:admin:reset-all-passwords');

        $this->entityManager  = $entityManager;
        $this->encoderFactory = $encoderFactory;
    }

    protected function configure(): void
    {
        $this->setDefinition([
            new InputArgument('password', InputArgument::REQUIRED),
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $encoder  = $this->encoderFactory->getEncoder(User::class);
        $password = $input->getArgument('password');
        $encodedPassword = $encoder->encodePassword($password, random_bytes(16));

        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare('UPDATE users SET password = ?');
        $stmt->bindValue(1, $encodedPassword);
        $stmt->execute();
        $output->writeln('Updated all users\' passwords');
    }
}
