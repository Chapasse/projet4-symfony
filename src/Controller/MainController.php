<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\ChambreRepository;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

    #[Route('/cartes', name:'cartes')]
    public function cartes()
    {
        return $this->render('main/cartes.html.twig');
    }

    #[Route('/spa', name:'spa')]
    public function spa()
    {
        return $this->render('main/spa.html.twig');
    }

    #[Route('avis', name:'avis')]
    public function avis(EntityManagerInterface $manager, Request $globals, AvisRepository $repo)
    {
        $avis = $repo->findAll();

        $comment = new Avis;
        $form=$this->createForm(AvisType::class, $comment);
        $form->handleRequest($globals);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setDateEnregistrement(new \DateTime);
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute("avis",[
                'avis' => $avis,
                'form' => $form,
            ]);
        }

        return $this->renderForm('main/avis.html.twig',[
            'avis' => $avis,
            'form' => $form,
        ]);
    }

}
