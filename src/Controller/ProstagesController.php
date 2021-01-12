<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Repository\EntrepriseRepository;
use App\Repository\StageRepository;

class ProstagesController extends AbstractController
{
    /**
     * @Route("/", name="prostages_accueil")
     */
    public function index(StageRepository $repoStage): Response
    {
      /*
        //Récupération du repo entite stage
        $repoStage = $this->getDoctrine()->getRepository(Stage::class);*/

        //Récupération des stages enregistrés
        $stages = $repoStage->findAll();

        //Envoyer les stages à la vue chargée de les afficher
        return $this->render('prostages/index.html.twig',['stages'=>$stages]);
    }

    /**
     * @Route("/entreprises", name="prostages_entreprises")
     */
    public function afficherEntreprises(EntrepriseRepository $repoEntreprises): Response
    {
        //$repoEntreprises = $this->getDoctrine()->getRepository(Entreprise::class);

        $entreprises = $repoEntreprises->findAll();

        //return new Response('<html><h1>Cette page affichera la liste des entreprises proposant un stage</h1></html>');
        return $this->render('prostages/affichageEntreprises.html.twig',['entreprises'=>$entreprises]);
    }

    /**
     * @Route("/formations", name="prostages_formations")
     */
    public function afficherFormations(): Response
    {
        //return new Response('<html><h1>Cette page affichera la liste des formations de l\'IUT</h1></html>');
        return $this->render('prostages/affichageFormations.html.twig');
    }

    /**
     * @Route("/stages/{id}", name="prostages_stage")
     */
    public function afficherStage(Stage $stage): Response
    {
        return $this->render('prostages/affichageStage.html.twig',
      ['stage' => $stage]);
    }
}
