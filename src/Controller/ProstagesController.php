<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Stage;

class ProstagesController extends AbstractController
{
    /**
     * @Route("/", name="prostages_accueil")
     */
    public function index(): Response
    {
        //Récupération du repo entite stage
        $repoStage = $this->getDoctrine()->getRepository(Stage::class);

        //Récupération des stages enregistrés
        $stages = $repoStage->findAll();

        //Envoyer les stages à la vue chargée de les afficher
        return $this->render('prostages/index.html.twig',['stages'=>$stages]);
    }

    /**
     * @Route("/entreprises", name="prostages_entreprises")
     */
    public function afficherEntreprises(): Response
    {
        //return new Response('<html><h1>Cette page affichera la liste des entreprises proposant un stage</h1></html>');
        return $this->render('prostages/affichageEntreprises.html.twig');
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
    public function afficherStage($id): Response
    {
        $repoStage = $this->getDoctrine()->getRepository(Stage::class);
        $stage = $repoStage->find($id);

        return $this->render('prostages/affichageStage.html.twig',
      ['stage' => $stage]);
    }
}
