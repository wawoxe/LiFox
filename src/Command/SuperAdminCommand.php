<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Command;

use function is_string;

use App\Entity\Security\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:create:super-admin',
    description: 'Creates a super admin in the application database.',
)]
final class SuperAdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ValidatorInterface $validator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'E-mail')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $email        = $input->getArgument('email');
        $password     = $input->getArgument('password');

        if (is_string($email) && is_string($password)) {
            $superAdminEntity = new User(
                email: $email,
                roles: ['ROLE_SUPER_ADMIN'],
            );

            $superAdminEntity->password = $password;

            $errors = $this->validator->validate($superAdminEntity);

            if ($errors->count()) {
                $symfonyStyle->error((string) $errors->get(0)->getMessage());

                return Command::FAILURE;
            }

            $superAdminEntity->password = $this->passwordHasher->hashPassword($superAdminEntity, $password);

            $this->manager->persist($superAdminEntity);
            $this->manager->flush();

            $symfonyStyle->success('Super admin was created successfully.');

            return Command::SUCCESS;
        }

        $symfonyStyle->error('Email or password was not provided.');

        return Command::FAILURE;
    }
}
