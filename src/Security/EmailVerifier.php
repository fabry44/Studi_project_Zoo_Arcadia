<?php

namespace App\Security;

use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Mailgun\Mailgun;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, Utilisateurs $user, TemplatedEmail $email): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            (string) $user->getId(),
            $user->getUsername(),
            ['id' => $user->getId()]
        );
        

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();
        $context['user'] = $user;

        $email->context($context);

        // $this->mailer->send($email);

        // Convertir le TemplatedEmail en un email texte et HTML simple pour Mailgun
        $plainTextBody = $email->getTextBody();
        $htmlBody = $email->getHtmlBody();

        // Envoi de l'email via Mailgun
        $mgClient = Mailgun::create($_ENV['MAILGUN_API_KEY']);
        $domain = $_ENV['MAILGUN_DOMAIN'];
        $params = [
            'from'    => 'Excited User <' . $_ENV['MAILGUN_FROM'] . '>',
            'to'      => 'Baz <' . $user->getUsername() . '>',
            'subject' => $email->getSubject(),
            'text'    => $plainTextBody,
            'html'    => $htmlBody
        ];

        try {
            $mgClient->messages()->send($domain, $params);
        } catch (\Exception $e) {
            // Gérer l'erreur de l'envoi d'email
            throw new \Exception('Une erreur est survenue lors de l\'envoi de l\'email de confirmation.');
        }
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, Utilisateurs $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, (string) $user->getId(), $user->getUsername());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
