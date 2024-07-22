<?php

namespace App\Controller;

use App\Repository\HabitatsRepository;
use App\Repository\ImgHabitatsRepository;
use App\Repository\AnimauxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HabitatsController extends AbstractController
{   
    public $habitatsRepository;
    public $animauxRepository;
    public $imgHabitatsRepository;

    public function __construct(HabitatsRepository $habitatsRepository, AnimauxRepository $animauxRepository, ImgHabitatsRepository $imgHabitatsRepository)
    {
        $this->habitatsRepository = $habitatsRepository;
        $this->animauxRepository = $animauxRepository;
        $this->imgHabitatsRepository = $imgHabitatsRepository;
    }

    #[Route('/habitats', name: 'app_habitats')]
    public function index(): Response
    {   
        $habitats = $this->habitatsRepository->findAll();

        $habitatsTables = [];

        // On récupère le nom, la description de l'habitat, les animaux présents et les images associées à chaque habitat
        foreach ($habitats as $habitat) {
            $images = [];
            foreach ($habitat->getImgHabitats() as $imgHabitat) {
                $images[] = 'uploads/habitats/' . $imgHabitat->getImageName();
            }

            $animals = [];
            foreach ($habitat->getAnimauxPresents() as $animal) {
                // Récupérer les images de l'animal
                $animalImages = [];
                foreach ($animal->getImgAnimaux() as $imgAnimal) {
                    $animalImages[] = 'uploads/animaux/' . $imgAnimal->getImageName();
                }

                // Récupérer le dernier rapport vétérinaire
                $latestRapport = null;
                if (!$animal->getRapportsVeterinaires()->isEmpty()) {
                    $latestRapport = $animal->getRapportsVeterinaires()->last();
                    foreach ($animal->getRapportsVeterinaires() as $rapport) {
                        if ($rapport->getDate() > $latestRapport->getDate()) {
                            $latestRapport = $rapport;
                        }
                    }
                }
                // On ajoute les informations de l'animal à la liste des animaux
                $animals[] = [
                    'prenom' => $animal->getPrenom(),
                    'habitat' => $animal->getHabitat()->getNom(),
                    'race' => $animal->getRace()->getLabel(),
                    'images' => $animalImages,
                    'latestRapport' => $latestRapport ? [
                        'etat' => $latestRapport->getEtat(),
                        'nourriture' => $latestRapport->getNourriture(),
                        'grammage' => $latestRapport->getGrammage(),
                        'detail' => $latestRapport->getDetail(),
                        'date' => $latestRapport->getDate()->format('Y-m-d'),
                        'veterinaire' => $latestRapport->getVeterinaire()->getNom(),
                    ] : null,
                ];
            }

            // On récupère les avis associés à l'habitat
            $avis = [];
            foreach ($habitat->getAvisHabitats() as $avisHabitat) {
                $avis[] = [
                    'avis' => $avisHabitat->getAvis(),
                    'date' => $avisHabitat->getDate()->format('Y-m-d'),
                    'veterinaire' => $avisHabitat->getVeterinaire()->getNom(),
                ];
            }
            // On ajoute enfin les informations de l'habitat à la liste des habitats
            $habitatsTables[$habitat->getNom()] = [
                'id' => $habitat->getId(),
                'description' => $habitat->getDescript(),
                'animaux' => $animals,
                'images' => $images,
                'avis' => $avis,
            ];
        }

        dump($habitatsTables);

        return $this->render('habitats/index.html.twig', [
            'habitatsTables' => $habitatsTables,
        ]);
    }
}
