<?php

namespace App\Controller\Admin\Dashboard;

use App\Document\Animal;
use Doctrine\ODM\MongoDB\DocumentManager;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Dto\AssetsDto;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[IsGranted('ROLE_ADMIN')]
class StatistiqueDashboardController extends AbstractDashboardController
{
    public $chartBuilder;
    public $dm;
    
    

    public function __construct(ChartBuilderInterface $chartBuilder, DocumentManager $dm) 
    {  
        $this->chartBuilder = $chartBuilder;
        $this->dm = $dm;
    }

    // ... you'll also need to load some CSS/JavaScript assets to render
    // the charts; this is explained later in the chapter about Design

    #[Route('/statistique-dashboard', name: 'statistique_dashboard')]
    public function index(): Response
    {   
        $animalView = $this->dm->getRepository(Animal::class)->findAll();
        $labels = [];
        $data = [];
        foreach ($animalView as $animal) {
            $labels[] = $animal->getPrenom();
            $data[] = $animal->getVue();
        }

        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nombre de vues',
                    'backgroundColor' => 'rgba(96, 141, 11, 0.743)',
                    'borderColor' => 'rgb(96, 141, 11)',
                    'data' => $data,
                ],
            ],
        ]);

        return $this->render('admin/statistique-dashboard.html.twig', [
            'chart' => $chart,
            'animals' => $labels,
            'vues' => $data
        ]);
    }

    
            
   
}
