<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Utilisateurs;
use App\Repository\UtilisateursRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create admin and store in database',
)]
class CreateAdminCommand extends Command
{
    private SymfonyStyle $io;
    private UtilisateursRepository $usersRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private EmailVerifier $emailVerifier;
    private EntityManagerInterface $entityManager;

    public function __construct(UtilisateursRepository $usersRepository, UserPasswordHasherInterface $userPasswordHasher, EmailVerifier $emailVerifier, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->usersRepository = $usersRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->emailVerifier = $emailVerifier;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::OPTIONAL, 'Email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password')
            ->addOption('nom', null, InputOption::VALUE_OPTIONAL, 'Nom')
            ->addOption('prenom', null, InputOption::VALUE_OPTIONAL, 'Prénom');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
        $admin = $this->usersRepository->findOneByRoles('administrateur');
        if ($admin) {
            $this->io->error('Un compte administrateur existe déjà.');
            exit(Command::FAILURE); // Arrête l'exécution de la commande
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (null === $input->getArgument('username') || null === $input->getArgument('password')) {
            $this->io->title('Création de l\'administrateur du site Zoo Arcadia');
            $this->io->text('Bonjour, vous allez créer le compte administrateur unique pour votre site Zoo Arcadia.');
            $this->askArgument($input, 'username', 'Quel est votre email ?');
            $this->askArgument($input, 'password', 'Quel est votre mot de passe ?');
            $this->askOption($input, 'nom', 'Quel est votre nom ?');
            $this->askOption($input, 'prenom', 'Quel est votre prénom ?');
        }
    }

    private function askArgument(InputInterface $input, string $argumentName, string $question): void
    {
        $value = $input->getArgument($argumentName);
        if (null === $value) {
            $value = $this->io->ask($question);
            $input->setArgument($argumentName, $value);
        }
    }

    private function askOption(InputInterface $input, string $optionName, string $question): void
    {
        $value = $input->getOption($optionName);
        if (null === $value) {
            $value = $this->io->ask($question);
            $input->setOption($optionName, $value);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {   
        
        $user = new Utilisateurs();
        $user->setUsername($input->getArgument('username'));
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $input->getArgument('password')
            )
        );
        $user->setNom($input->getOption('nom'));
        $user->setPrenom($input->getOption('prenom'));
        $user->setRoles('administrateur'); // Assurez-vous que les rôles sont un tableau
        $user->setIsVerified(false);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('contact@zoo-arcadia.com', 'Support Zoo Arcadia'))
                ->to($user->getUsername())
                ->subject('Merci de comfirmer votre Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        $this->io->success('Le compte administrateur a été créé avec succès.');

        return Command::SUCCESS;
    }
}
