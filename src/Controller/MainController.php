<?php

namespace App\Controller;

use App\Repository\ChambreRepository;
use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/')]
    public function root()
    {
        return $this->redirectToRoute('app_main');
    }
    
    #[Route('/main', name: 'app_main')]
    public function index(SliderRepository $repo): Response
    {
        $sliders = $repo->findAll();

        return $this->render('main/index.html.twig', [
            'sliders' => $sliders,
        ]);
    }

    #[Route('/chambres', name:'chambres')]
    public function chambres(ChambreRepository $repo): Response
    {
        $chambres = $repo->findAll();

        return $this->render('main/chambres.html.twig', [
            'chambres' => $chambres,
        ]);
    }
}
