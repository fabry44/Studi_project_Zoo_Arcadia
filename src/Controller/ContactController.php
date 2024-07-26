<?php

namespace App\Controller;

use App\Form\ContactType;
use Mailgun\Mailgun;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

             // Utiliser Mailgun pour envoyer l'email
            $mgClient = Mailgun::create($_ENV['MAILGUN_API_KEY']);
            $domain = $_ENV['MAILGUN_DOMAIN'];
            $params = [
                'from'    => $_ENV['contact@arcadia.com'],
                'to'      => $_ENV['fabienroy2@gmail.com'],
                'subject' => $data['title'],
                'text'    => $data['description']
            ];

            try {
                $mgClient->messages()->send($domain, $params);
                $this->addFlash('success', 'Votre demande a été envoyée avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre demande.');
            }

            // Envoi de l'email au zoo
            // $email = (new Email())
            //     ->from($data['email'])
            //     ->to('fabienroy2@gmail.com') // Remplacez par l'adresse email du zoo
            //     ->subject($data['title'])
            //     ->text($data['description']);

            // $mailer->send($email);

            // Ajouter un message flash
            // $this->addFlash('success', 'Votre demande a été envoyée avec succès.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
