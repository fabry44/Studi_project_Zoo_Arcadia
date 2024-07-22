<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, AvisRepository $avisRepository): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setValide(false); // Les avis doivent être validés avant d'être affichés
            $entityManager->persist($avis);
            $entityManager->flush();

            // Si le formulaire est soumis via Turbo, renvoyez une réponse Turbo Stream
            if ($request->isXmlHttpRequest()) {
                return $this->render('avis/_new_avis.html.twig', [
                    'avis' => $avis,
                    'form' => $form->createView(), // Pour afficher les erreurs de validation
                ]);
            }

            $this->addFlash('message', 'Votre avis a été soumis avec succès et est en attente de validation.');

            return $this->redirectToRoute('app_avis');
        }

        $avisValides = $avisRepository->findBy(['valide' => true]);

        return $this->render('avis/_new_avis.html.twig', [
            'AvisValides' => $avisValides,
            'form' => $form->createView(),
        ]);
    }
}
