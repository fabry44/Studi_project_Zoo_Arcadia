<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ImgServicesRepository;
use App\Repository\ServicesRepository;
use App\Repository\ImgHabitatsRepository;
use App\Repository\HabitatsRepository;
use App\Repository\ImgAnimauxRepository;
use App\Repository\AnimauxRepository;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class MainController extends AbstractController
{   
    public $imgServicesRepository;
    public $servicesRepository;
    public $imgHabitatsRepository;
    public $habitatsRepository;
    public $imgAnimauxRepository;
    public $animauxRepository;
    public $avisRepository;

    public function __construct(ImgServicesRepository $imgServicesRepository, ServicesRepository $servicesRepository, ImgHabitatsRepository $imgHabitatsRepository, HabitatsRepository $habitatsRepository, ImgAnimauxRepository $imgAnimauxRepository, AnimauxRepository $animauxRepository, AvisRepository $avisRepository)
    {
        $this->imgServicesRepository = $imgServicesRepository;
        $this->servicesRepository = $servicesRepository;
        $this->imgHabitatsRepository = $imgHabitatsRepository;
        $this->habitatsRepository = $habitatsRepository;
        $this->imgAnimauxRepository = $imgAnimauxRepository;
        $this->animauxRepository = $animauxRepository;
        $this->avisRepository = $avisRepository;

    }

    #[Route('/', name: 'app_main_index')]
    public function index(Request $request, EntityManagerInterface $entityManager,): Response
    {   
        /* *************************************************************** */
/*                                 SERVICES                                */
        /* *************************************************************** */
        /*
        Retourne les services avec une de leurs images associées récupérée au hasard 
        */

        // On récupère toutes les images de services
        $imagesServices = $this->imgServicesRepository->findAll();
    
        // On récupère les images des services et leurs noms
        $selectedimagesServices = [];
        foreach ($imagesServices as $imageService) {
            $nomService = $imageService->getServices()->getNom();
            $selectedimagesServices[$nomService][] = 'uploads/services/' . $imageService->getImageName();
        }

        // On filtre les services qui ont des images disponibles et on sélectionne une seule image par service
        $servicesWithImages = [];
        foreach ($selectedimagesServices as $nomService => $images) {
            $randomImageIndex = array_rand($images);
            $servicesWithImages[$nomService] = $images[$randomImageIndex];
        }


        /* *************************************************************** */
        /*                          HABITATS                               */
        /* *************************************************************** */
        /*
        Retourne les habitats avec une de leurs images associées récupérée au hasard 
        */

        // On récupère toutes les images des habitats
        $imagesHabitats = $this->imgHabitatsRepository->findAll();
    
        // On récupère les images des habitats et leurs noms
        $selectedimagesHabitats = [];
        foreach ($imagesHabitats as $imagesHabitat) {
            $nomHabitat = $imagesHabitat->getHabitat()->getNom();
            $selectedimagesHabitats[$nomHabitat][] = 'uploads/habitats/' . $imagesHabitat->getImageName();
        }

        // On filtre les habitats qui ont des images disponibles et on sélectionne une seule image par habitat
        $habitatsWithImages = [];
        foreach ($selectedimagesHabitats as $nomService => $images) {
            $randomImageIndex = array_rand($images);
            $habitatsWithImages[$nomService] = $images[$randomImageIndex];
         }


        /* *************************************************************** */
        /*                            ANIMAUX                              */
        /* *************************************************************** */
        /*
        Retourne 3 animaux avec une de leurs images associées récupérée au hasard 
        */

        // On récupère toutes les images de animaux
        $imagesAnimaux = $this->imgAnimauxRepository->findAll();
    
        // On récupère les images d'animaux et leurs prénoms
        $selectedimagesAnimaux = [];
        foreach ($imagesAnimaux as $imagesAnimal) {
            $nomAnimaux = $imagesAnimal->getAnimal()->getPrenom();
            $selectedimagesAnimaux[$nomAnimaux][] = 'uploads/animaux/' . $imagesAnimal->getImageName();
        }
       
        // On filtre les animaux qui ont des images disponibles et on sélectionne une seule image par animal
        $animalsWithImages = [];
        foreach ($selectedimagesAnimaux as $animalName => $images) {
            $randomImageIndex = array_rand($images);
            $animalsWithImages[$animalName] = $images[$randomImageIndex];
        }

        // On Sélectionne le nombre d'animaux à afficher (entre 1 et 3)
        $numAnimalsToShow = min(3, count($animalsWithImages));
        
        // On Sélectionne aléatoirement le nombre d'animaux à afficher
        $randomAnimalsKeys = array_rand($animalsWithImages, $numAnimalsToShow);
        
        // On S'assure que $randomAnimalsKeys soit toujours un tableau
        if (!is_array($randomAnimalsKeys)) {
            $randomAnimalsKeys = [$randomAnimalsKeys];
        }
       
        // On récupère enfin les animaux sélectionnés avec leurs images
        $selectedAnimals = [];
        foreach ($randomAnimalsKeys as $key) {
            $animalName = $key;
            $selectedAnimals[$animalName] = $animalsWithImages[$animalName];
        }

        /* *************************************************************** */
        /*                            Avis clientèle                       */                              
        /* *************************************************************** */
        /*
        Retourne les avis de la clientèle validés par l'employé
        */
        $AvisValide = $this->avisRepository->findBy(['valide' => true]);
       

        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setValide(false); 
            $entityManager->persist($avis);
            $entityManager->flush();

            $this->addFlash('success', 'Votre avis a été soumis avec succès et est en attente de validation.');

            return $this->redirectToRoute('app_main_index');
        }

        /* *************************************************************** */
        /*                            Contact                              */
        /* *************************************************************** */


        return $this->render('main/index.html.twig', [
            'selectedimagesServices' => $servicesWithImages,
            'selectedimagesHabitats' => $habitatsWithImages,
            'selectedAnimals' => $selectedAnimals,
            'AvisValides' => $AvisValide,
            'form' => $form->createView(),
        ]);
    }
}
