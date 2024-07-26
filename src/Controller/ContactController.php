<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, LoggerInterface $logger): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Log the data for debugging
            $logger->info('Form data: ', $data);

            // Utiliser Guzzle pour envoyer l'email via l'API HTTP de Mailgun
            $client = new Client();
            $apiKey = $_ENV['MAILGUN_API_KEY'];
            $domain = $_ENV['MAILGUN_DOMAIN'];
            $url = "https://api.mailgun.net/v3/$domain/messages";

            try {
                $response = $client->post($url, [
                    'auth' => ['api', $apiKey],
                    'form_params' => [
                        'from' => $_ENV['MAILGUN_FROM'],
                        'to' => $_ENV['MAILGUN_TO'],
                        'subject' => $data['title'],
                        'text' => $data['description']
                    ]
                ]);

                $logger->info('Mailgun response: ' . $response->getBody());
                $this->addFlash('success', 'Votre demande a été envoyée avec succès.');
            } catch (\Exception $e) {
                $logger->error('Error sending email: ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre demande.');
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
