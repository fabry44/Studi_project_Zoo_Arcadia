<?php

namespace App\EventSubscriber;

use App\Entity\Utilisateurs;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UtilisateursSubscriber implements EventSubscriberInterface
{
    private $passwordHasher;
    private $entityManager;
    private $emailVerifier;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, EmailVerifier $emailVerifier)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->emailVerifier = $emailVerifier;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUserPasswordAndSendEmail'],
        ];
    }

    public function setUserPasswordAndSendEmail(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Utilisateurs)) {
            return;
        }

        // Encoder le mot de passe
        $plainPassword = $entity->getPassword();
        if ($plainPassword !== null) {
            $entity->setPassword(
                $this->passwordHasher->hashPassword(
                    $entity,
                    $plainPassword
                )
            );
        }

        $entity->setIsVerified(false);

        // Persist l'entité dans la base de données 
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        // Envoi d'un email de confirmation
        $email = (new TemplatedEmail())
            ->from(new Address('contact@zoo-arcadia.com', 'Support Zoo Arcadia'))
            ->to($entity->getUsername())
            ->subject('Merci de confirmer votre Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $entity, $email);
    }
}
