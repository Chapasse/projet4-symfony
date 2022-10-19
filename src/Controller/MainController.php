<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Form\ContactType;
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

    #[Route('avis/filtre', name:'avis_filtre')]
    #[Route('avis', name:'avis')]
    public function avis(EntityManagerInterface $manager, Request $globals, AvisRepository $repo, $category = null)
    {
        if($globals->request->get('category') != null):
            $category = $globals->request->get('category');
        endif;
        $avisFiltre = $repo->findBy(["category" => $category]);
        $avis = $repo->findAll();
        
        $comment = new Avis;
        $form=$this->createForm(AvisType::class, $comment);
        $form->handleRequest($globals);

        if($form->isSubmitted() && $form->isValid())
        {
            if($comment->getNote()>5 || $comment->getNote()<0)
            {
                $this->addFlash('warning', "⚠ la note doit être comprise entre 0 et 5");
                return $this->redirectToRoute('avis');
            }
            
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
            'avisFiltre' => $avisFiltre,
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('actualites', name:'actualites')]
    public function actualites()
    {
        $rss = simplexml_load_file('https://www.lhotellerie-restauration.fr/rss/actu_rss.xml?xtor=RSS-1');
        return $this->render('main/actualites.html.twig',[
            'rssItems' => $rss->channel->item
        ]);
    }

    #[Route('/qui-sommes-nous', name:'who')]
    #[Route('contact', name:'contact')]
    public function contact(Request $globals, $contact = null)
    {

        $form=$this->createForm(ContactType::class);
        $form->handleRequest($globals);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash('success', "votre demande de contact a bien été envoyé");
            return $this->redirectToRoute("app_main");
        }
        $routeName = $globals->get('_route');
        if($routeName == 'contact')
        {
            $contact = 1;
        }

        return $this->renderForm('main/contact.html.twig',[
            'affiche' => $contact,
            'form' => $form,
        ]);
 
    }

    #[Route('/newsletter', name:'newsletter')]
    public function newsletter()
    {
        $this->addFlash('success', "Vous êtes bien inscris à la newsletter");
        return $this->redirectToRoute('app_main');
    }

}
