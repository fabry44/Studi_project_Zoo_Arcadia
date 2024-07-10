<?php

namespace App\Tests\Admin\Controller;

use App\Controller\Admin\Crud\UtilisateursCrudController;
use App\Controller\Admin\Dashboard\AdminDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use App\Tests\Traits\DatabaseTrait;
use App\Tests\Traits\UserLoginTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UtilisateursCrudControllerTest extends AbstractCrudTestCase
{   
    use DatabaseTrait;
    use UserLoginTrait;

    private $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Récupérer le container de l'application
        $container = static::getContainer();

        // Initialiser la base de données
        $this->initializeDatabase();
        
        // Initialiser le password hasher
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);
        
        // Initialiser les utilisateurs
        $this->initializeUsers($this->entityManager, $this->passwordHasher);

        // Se connecter avec l'utilisateur admin
        $this->loginAdmin($this->client, $container);
    }

    protected function getControllerFqcn(): string
    {
        return UtilisateursCrudController::class;
    }

    protected function getDashboardFqcn(): string
    {
        return AdminDashboardController::class;
    }

    public function testIndexPage(): void
    {
        // non autorisé - Impossible de se connecter avec mauvais mot de passe.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();


        // Success - le login est réussi
        $this->client->submitForm('Se connecter', [
            '_username' => 'email.admin@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/admin-dashboard');
        // Effectuer la requête GET sur la page d'index
        $this->client->request("GET", $this->generateIndexUrl());
        static::assertResponseIsSuccessful();
        
    }

    protected function tearDown(): void
    {
        $this->dropDatabase();
        parent::tearDown();
    }
}
