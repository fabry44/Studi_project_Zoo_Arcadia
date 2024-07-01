<?php

namespace App\Tests;

use App\Repository\UtilisateursRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mailer\Tests\Transport;
class RegistrationControllerTest extends WebTestCase
{
    use MailerAssertionsTrait;
    
    private KernelBrowser $client;
    private UtilisateursRepository $userRepository;
    private $emailVerifier;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Ensure we have a clean database
        $container = static::getContainer();

        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        $this->userRepository = $container->get(UtilisateursRepository::class);

        foreach ($this->userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();
    }

    public function testRegister(): void
    {
        // Register a new user
        $crawler = $this->client->request('GET', '/register');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Register');

        $this->client->submitForm('Register', [
            'registration_form[username]' => 'fabienroy2@gmail.com',
            'registration_form[nom]' => 'Doe',
            'registration_form[prenom]' => 'John',
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true,
        ]);
        
        // Ensure the response redirects after submitting the form
        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        // Check if the user was created and is not verified
        self::assertCount(1, $this->userRepository->findAll());
        $user = $this->userRepository->findAll()[0];
        self::assertFalse($user->isVerified());

        // Ensure the verification email was sent
        // self::assertQueuedEmailCount(1, 'smtp:null');
        // self::assertEmailCount(1, 'null://null');

        // $email = $this->getMailerMessage(0);
        // self::assertEmailAddressContains($email, 'from', 'fabienroy2@gmail.com');
        // self::assertEmailAddressContains($email, 'to', 'fabienroy2@gmail.com');
        // self::assertEmailTextBodyContains($email, 'This link will expire in 1 hour.');

        // // Get the verification link from the email
        // /** @var TemplatedEmail $templatedEmail */
        // $templatedEmail = $email;
        // $messageBody = $templatedEmail->getHtmlBody();
        // self::assertIsString($messageBody);

        // preg_match('#(127.0.0.1:8000/verify/email/.+)#', $messageBody, $resetLink);
        // self::assertArrayHasKey(1, $resetLink);

        // // "Click" the link and see if the user is verified
        // $this->client->request('GET', $resetLink[1]);
        // $this->client->followRedirect();

        // $verifiedUser = $this->userRepository->find($user->getId());
        // self::assertTrue($verifiedUser->isVerified());TODO
    }
}
