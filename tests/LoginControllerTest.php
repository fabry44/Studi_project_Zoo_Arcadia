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

        // Remove any existing users from the test database
        foreach ($userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();

        // Create a Utilisateurs fixture
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get('security.user_password_hasher');

        $user = (new Utilisateurs())->setUsername('email.admin@example.com');
        $user->setNom('Doe');
        $user->setPrenom('John');
        $user->setRoles('ROLE_ADMIN');
        $user->setIsVerified(true);
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));

        $em->persist($user);
        $em->flush();

        $user = (new Utilisateurs())->setUsername('email.veterinaire@example.com');
        $user->setNom('dupont');
        $user->setPrenom('jean');
        $user->setRoles('ROLE_VETERINAIRE');
         $user->setIsVerified(true);
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));

        $em->persist($user);
        $em->flush();

        $user = (new Utilisateurs())->setUsername('email.employe@example.com');
        $user->setNom('henry');
        $user->setPrenom('bernard');
        $user->setRoles('ROLE_EMPLOYE');
         $user->setIsVerified(false);
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));

        $em->persist($user);
        $em->flush();
    }

    public function testLogin(): void
    {
        // Denied - Can't login with invalid email address.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => 'doesNotExist@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        // Ensure we do not reveal if the user exists or not.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Denied - Can't login with invalid password.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => 'email.employe@example.com',
            '_password' => 'bad-password',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        // Ensure we do not reveal the user exists but the password is wrong.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Success - Login with valid credentials is allowed.
        $this->client->submitForm('Sign in', [
            '_username' => 'email.admin@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/admin');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        self::assertResponseIsSuccessful();
    }
}
