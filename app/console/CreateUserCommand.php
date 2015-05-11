<?php

namespace App\Console;

use App\Model\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateUserCommand
 *
 * @package ldrahnik\nette-todomvc
 * @author Lukáš Drahník <http://ldrahnik.com>
 */
class CreateUserCommand extends Command
{
	protected function configure()
	{
		$this->setName('app:create-user')
			->setDescription('Create user')
			->addArgument(
				'username',
				InputArgument::REQUIRED,
				'Username'
			)
			->addArgument(
				'password',
				InputArgument::REQUIRED,
				'User password'
			);
	}

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getHelper('container')->getByType('\Kdyby\Doctrine\EntityManager');

        try {
            $username = $input->getArgument('username');

            $user = new User($input->getArgument('username'), $input->getArgument('password'));
            $em->persist($user);
            $em->flush();

            $output->writeLn("User $username has been created.");
            return 0;

        } catch (UniqueConstraintViolationException $e) {
            $output->writeLn("Username $username is already used. Please try it again :]");
            return 1;
        }
    }
}