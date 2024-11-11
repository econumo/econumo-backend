<?php

declare(strict_types=1);

namespace App\EconumoBundle\Command;

use App\EconumoBundle\Domain\Entity\ValueObject\Email;
use App\EconumoBundle\Domain\Factory\UserFactoryInterface;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    protected static $defaultDescription = 'Create new user';

    public function __construct(private readonly UserFactoryInterface $userFactory, private readonly UserRepositoryInterface $userRepository)
    {
        parent::__construct(self::$defaultName);
    }


    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('email', InputArgument::REQUIRED, 'E-mail')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = trim((string) $input->getArgument('name'));
        $email = trim((string) $input->getArgument('email'));
        $password = trim((string) $input->getArgument('password'));

        $user = $this->userFactory->create($name, new Email($email), $password);
        $this->userRepository->save([$user]);
        $io->success(sprintf('User %s successfully created! (id: %s)', $name, $user->getId()));

        return Command::SUCCESS;
    }
}
