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


class UtilisateursSubscriberTest extends WebTestCase
{
    private KernelBrowser $client;
    private $passwordHasher;
    private $emailVerifier;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        $session = new Session(new MockArraySessionStorage());
        $container->set('session', $session);

        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);
        $this->emailVerifier = $container->get(EmailVerifier::class);

        $userRepository = $container->get('doctrine')->getRepository(Utilisateurs::class);
        $adminUser = $userRepository->findOneBy(['username' => 'email.admin@example.com']);
        $roles = $adminUser->getRoles();
        dump($roles);
        $token = new UsernamePasswordToken(
            $adminUser,           
            'main',
            $roles,
            'password',
        );

        $container->get('security.token_storage')->setToken($token);

        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function testCreateUser()
    {   
        $this->client->request('GET', '/login');
        $this->client->submitForm('Sign in', [
            '_username' => 'email.admin@example.com',
            '_password' => 'password',
        ]);
        self::assertResponseRedirects('/admin');
        $this->client->followRedirect();
        $crawler = $this->client->request('GET', '/admin?crudAction=new&crudControllerFqcn=App\Controller\Admin\Crud\UtilisateursCrudController');

        $this->assertResponseIsSuccessful("La page n'a pas été chargée comme prévu.");
        // Ensure that the button with the correct name exists and can be interacted with
        $buttonCrawlerNode = $crawler->selectButton('Create');
        $this->assertNotNull($buttonCrawlerNode, "The 'submit' button was not found.");

        // Submit the form with the correct data
        $this->client->submitForm('Create', [
            'Utilisateurs[username]' => 'test@example.com',
            'Utilisateurs[password]' => 'plainPassword',
            'Utilisateurs[nom]' => 'NomTest',
            'Utilisateurs[prenom]' => 'PrenomTest',
            'Utilisateurs[roles]' => ['ROLE_EMPLOYE'], // Ensure that the roles field is correctly structured
        ]);

        // $this->client->submit($form);
        // $this->assertResponseRedirects('/admin');
        // $this->client->followRedirect();
        // $this->assertSelectorTextContains('.flash-notice', 'The user has been created.');

         $user = static::getContainer()->get('doctrine')->getRepository(Utilisateurs::class)->findOneBy(['username' => 'test@example.com']);
        $this->assertNotNull($user);
        $this->assertTrue($this->passwordHasher->isPasswordValid($user, 'plainPassword'));
        $this->assertFalse($user->isVerified());

         // Configuration des attentes sur le mock et invocation
        // $this->emailVerifier->expects($this->once())
        //                     ->method('sendEmailConfirmation')
        //                     ->with(
        //                         $this->equalTo('app_verify_email'),
        //                         $this->equalTo($user),
        //                         $this->isInstanceOf(TemplatedEmail::class)
        //                     );

        // // Exécuter la méthode qui doit être testée
        // $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, new TemplatedEmail());
    }

    
}
