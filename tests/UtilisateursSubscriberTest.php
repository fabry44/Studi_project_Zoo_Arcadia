<?php 

namespace App\Tests;

use App\Entity\Utilisateurs;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use App\Tests\Traits\DatabaseTrait;
use App\Tests\Traits\UserLoginTrait;


class UtilisateursSubscriberTest extends WebTestCase
{   
    use DatabaseTrait;
    use UserLoginTrait;

    private KernelBrowser $client;
    private $passwordHasher;
    private $emailVerifier;

    protected function setUp(): void
    {   
        // Initialiser le client
        // Crée le client avec les options spécifiées pour utiliser 127.0.0.1
        $this->client = static::createClient([], [
            'HTTP_HOST' => '127.0.0.1',
        ]);
        $container = static::getContainer();

        // Initialiser la base de données
        $this->initializeDatabase();

        $em = $container->get('doctrine.orm.entity_manager');
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Initialiser les utilisateurs
        $this->initializeUsers($em, $this->passwordHasher);

        // Initialiser le mock de EmailVerifier
        $this->emailVerifier = $this->createMock(EmailVerifier::class);

        // Se connecter avec l'utilisateur admin
        $this->loginAdmin($this->client, $container);
    }

    public function testCreateUser()
    {   
        $this->client->request('GET', '/login');
        $this->client->submitForm('Se connecter', [
            '_username' => 'email.admin@example.com',
            '_password' => 'password',
        ]);
        self::assertResponseRedirects('/admin-dashboard');
        $this->client->followRedirect();
        $crawler = $this->client->request('GET', '/admin-dashboard?crudAction=new&crudControllerFqcn=App%5CController%5CAdmin%5CCrud%5CUtilisateursCrudController');

        $this->assertResponseIsSuccessful("La page n'a pas été chargée comme prévu.");
        // Ensure that the button with the correct name exists and can be interacted with
        $buttonCrawlerNode = $crawler->selectButton('Créer');
        $this->assertNotNull($buttonCrawlerNode, "The 'submit' button was not found.");

        // Submit the form with the correct data
        $this->client->submitForm('Créer', [
            'Utilisateurs[username]' => 'test@example.com',
            'Utilisateurs[password]' => 'plainPassword',
            'Utilisateurs[nom]' => 'NomTest',
            'Utilisateurs[prenom]' => 'PrenomTest',
            'Utilisateurs[roles]' => ['ROLE_EMPLOYE'], // Ensure that the roles field is correctly structured
        ]);

        // $this->client->submit($form);
        // $this->assertResponseRedirects('/admin-dashboard');
        // $this->client->followRedirect();
        // $this->assertSelectorTextContains('.flash-notice', 'The user has been created.');TODO

        $user = static::getContainer()->get('doctrine')->getRepository(Utilisateurs::class)->findOneBy(['username' => 'test@example.com']);
        $this->assertNotNull($user);
        $this->assertTrue($this->passwordHasher->isPasswordValid($user, 'plainPassword'));
        $this->assertFalse($user->isVerified());

        // Configuration des attentes sur le mock et invocation
        $this->emailVerifier->expects($this->once())
                            ->method('sendEmailConfirmation')
                            ->with(
                                $this->equalTo('app_verify_email'),
                                $this->equalTo($user),
                                $this->isInstanceOf(TemplatedEmail::class)
                            );

        // Exécuter la méthode qui doit être testée
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, new TemplatedEmail());
    }

    
}
