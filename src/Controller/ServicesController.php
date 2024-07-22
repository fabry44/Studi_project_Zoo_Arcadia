<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ServicesRepository;

class ServicesController extends AbstractController
{   
    public $servicesRepository;

    public function __construct(ServicesRepository $servicesRepository)
    {
        $this->servicesRepository = $servicesRepository;
    }

    #[Route('/services', name: 'app_services')]
    public function index(): Response
    {   
        $services = $this->servicesRepository->findAll();

        $servicesTables = [];

        // On récupère l'id,le nom, la description du service et les images associées à chaque service
        foreach ($services as $service) {
            $images = [];
            foreach ($service->getImgServices() as $imgService) {
                $images[] = 'uploads/services/' . $imgService->getImageName();
            }

            $servicesTables[] = [
                'id' => $service->getId(),
                'nom' => $service->getNom(),
                'description' => $service->getDescript(),
                'images' => $images
            ];
        }

        return $this->render('services/index.html.twig', [
            'servicesTables' => $servicesTables,
        ]);
    }
}
