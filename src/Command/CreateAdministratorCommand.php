<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


// le commande à taper : symfony console app:create-administrator
#[AsCommand(
    name: 'app:create-administrator', //nom de la commande 
    description: 'Permet de créer une commande', //le descriptif de la commande
)]
class CreateAdministratorCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    //cette fonction est facultatif pour la commande marche 
    protected function configure(): void
    {
        $this
           // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description') //un argument de commande comme l'est upgrade dans la commande [apt upgrate] de linux
           // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description') //option de commande comme les -y dans la commande [apt upgrate -y] de linux

            //on ajoute nos arguments et options
           ->addArgument('username', InputArgument::OPTIONAL, 'Le pseudo')
           ->addArgument('email', InputArgument::OPTIONAL, 'Email') 
           ->addArgument('password', InputArgument::OPTIONAL, 'Mot de passe')
        ;
    }

    //la fonction qui sera éxécutée
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        $io = new SymfonyStyle($input, $output);
        $usernameArgument = $input->getArgument('username'); //on déclare l'argument username
        $emailArgument = $input->getArgument('email'); //on déclare l'argument username
        $plainPasswordArgument = $input->getArgument('password'); //on déclare l'argument username

        //on va créer un nouveau utilisateur 
        $user = (new User())
        ->setUsername($usernameArgument)
        ->setEmail($emailArgument)
        ->setPassword($plainPasswordArgument)
        ->setRoles(['ROLE_USER','ROLE_ADMIN']); //avec les arguments données on passe notre user en administrateur
/*
        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }
*/
        /*
        if ($input->getOption('option1')) {
            // ...
        }
*/

           // dd($arg1, $arg2,  $arg3);
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
