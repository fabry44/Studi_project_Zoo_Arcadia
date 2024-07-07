<?php

namespace App\Tests;

use App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Tests\Traits\DatabaseTrait;
use App\Tests\Traits\UserLoginTrait;

class LoginControllerTest extends WebTestCase
{
    use DatabaseTrait;
    use UserLoginTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        // Initialiser le client
        $this->client = static::createClient();
        $container = static::getContainer();

        // Initialiser la base de données
        $this->initializeDatabase();

        $em = $container->get('doctrine.orm.entity_manager');
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Initialiser les utilisateurs
        $this->initializeUsers($em, $passwordHasher);

        // Se connecter avec l'utilisateur admin
        $this->loginAdmin($this->client, $container);
    }

    public function testLogin(): void
    {
        // non autorisé - Impossible de se connecter avec un utilisateur inexistant.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Log in', [
            '_username' => 'doesNotExist@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        // S'assurer de ne pas révéler que l'utilisateur existe ou pas.
        self::assertSelectorTextContains('.alert-danger', 'Identifiants invalides.');

        // non autorisé - Impossible de se connecter avec mauvais mot de passe.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Log in', [
            '_username' => 'email.employe@example.com',
            '_password' => 'bad-password',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        // S'assurer de ne pas révéler que le mot de passe est incorrect et que le username existe bien.
        self::assertSelectorTextContains('.alert-danger', 'Identifiants invalides.');

        // Success - le login est réussi
        $this->client->submitForm('Log in', [
            '_username' => 'email.admin@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/admin-dashboard');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}
