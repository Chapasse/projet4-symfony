<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

class ShowController extends AbstractController
{
    #[Route('/chambre/{id}', name: 'show_chambre')]
    public function showChambre($id, ChambreRepository $repo, Commande $commande = null, EntityManagerInterface $manager, Request $rq): Response
    {
        $chambre = $repo->find($id);
        $chambres = $repo->findAll();

        
        $commande = new Commande;
        $form = $this->createForm(CommandeType::class, $commande);
        
        $form->handleRequest($rq);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setChambre($chambre);
            $commande->setDateEnregistrement(new \DateTime);
            $depart = $commande->getDateArrivee();

            if ($depart->diff($commande->getDateDepart())->invert == 1) {
                $this->addFlash('danger', 'Une période de temps ne peut pas être négative.');
                return $this->redirectToRoute('show_chambre',[
                    'id' => $chambre->getId()
                ]);
            }
            // cette condition vérifie que les dates réservées sont correctes

        
            $days = $depart->diff($commande->getDateDepart())->days;
            $prixTotal = ($commande->getChambre()->getPrixJournalier() * $days) + $commande->getChambre()->getPrixJournalier();
            // récupère le prix total (sans la dernière addition, il manque un jour à payer)

            $commande->setPrixTotal($prixTotal);

            $manager->persist($commande);
            $manager->flush();
            $this->addFlash('success', 'La commande a bien été passée !');
            return $this->redirectToRoute('app_main');
        }
        return $this->renderForm("show/chambre.html.twig", [
            'chambres' => $chambres,
            "chambre" => $chambre,
            "id" => $chambre->getId(),
            'form' => $form,
        ]);
    }

    #[Route('/mentions', name:'mentions')]
    public function mentions()
    {
        return $this->render('footer/mentions.html.twig');
    }

    #[Route('/plan', name:'plan')]
    public function plan()
    {
        return $this->render('footer/plan.html.twig');
    }

    #[Route('/conditions', name:'conditions')]
    public function conditions()
    {
        return $this->render('footer/conditions.html.twig');

    }
    #[Route('/print', name:'print')]
    public function print()
    {
        
        return $this->render('footer/print.html.twig');
    }



}
