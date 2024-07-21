<?php

namespace App\Controller;

use App\Document\Animal;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VueAnimalController extends AbstractController
{
    #[Route('/animal/view/{prenom}', name: 'app_animal_view')]
    public function viewAnimal(string $prenom, DocumentManager $dm): Response
    {
        $animalView = $dm->getRepository(Animal::class)->findOneBy(['prenom' => $prenom]);

        if (!$animalView) {
            $animalView = new Animal();
            $animalView->setPrenom($prenom);
            $dm->persist($animalView);
        }

        $animalView->incrementVue();
        $dm->flush();

        return $this->json(['viewCount' => $animalView->getVue()]);
    }
}
