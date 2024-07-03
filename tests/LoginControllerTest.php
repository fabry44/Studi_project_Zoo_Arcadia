<?php

namespace App\Tests;

use App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $userRepository = $em->getRepository(Utilisateurs::class);

        // supprimer tous les utilisateurs de la base de données avant de commencer les tests de connexion
        foreach ($userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();

        // Créer un utilisateur admin
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get('security.user_password_hasher');

        $user = (new Utilisateurs())->setUsername('email.admin@example.com');
        $user->setNom('Doe');
        $user->setPrenom('John');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));

        $em->persist($user);
        $em->flush();

        // Créer un utilisateur vétérinaire
        $user = (new Utilisateurs())->setUsername('email.veterinaire@example.com');
        $user->setNom('dupont');
        $user->setPrenom('jean');
        $user->setRoles(['ROLE_VETERINAIRE']);
         $user->setIsVerified(true);
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));

        $em->persist($user);
        $em->flush();

        // Créer un utilisateur employé
        $user = (new Utilisateurs())->setUsername('email.employe@example.com');
        $user->setNom('henry');
        $user->setPrenom('bernard');
        $user->setRoles(['ROLE_EMPLOYE']);
         $user->setIsVerified(false);
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));

        $em->persist($user);
        $em->flush();
    }

    public function testLogin(): void
    {
        // non autorisé - Impossible de se connecter avec un utilisateur inexistant.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => 'doesNotExist@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        // S'assurer de ne pas révéler que l'utilisateur existe ou pas.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // non autorisé - Impossible de se connecter avec mauvais mot de passe.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => 'email.employe@example.com',
            '_password' => 'bad-password',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        // S'assurer de ne pas révéler que le mot de passe est incorrect et que le username existe bien.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // non autorisé - Impossible de se connecter avec un utilisateur non vérifié.
        // $this->client->request('GET', '/login');
        // self::assertResponseIsSuccessful();

        // $this->client->submitForm('Sign in', [
        //     '_username' => 'email.veterinaire@example.com',
        //     '_password' => 'password',
        // ]);

        // self::assertResponseRedirects('/login');TODO 
        // $this->client->followRedirect();

        // Success - le login est réussi
        $this->client->submitForm('Sign in', [
            '_username' => 'email.admin@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/admin');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        // self::assertResponseIsSuccessful();
    }
}
