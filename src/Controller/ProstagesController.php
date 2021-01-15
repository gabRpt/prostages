<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Repository\EntrepriseRepository;
use App\Repository\StageRepository;
use App\Repository\FormationRepository;

class ProstagesController extends AbstractController
{
    /**
     * @Route("/", name="prostages_accueil")
     */
    public function index(StageRepository $repoStage): Response
    {
        //Récupération des stages enregistrés, reposStage est directement récupéré
        //Grâce à l'injection de dépendances
        $stages = $repoStage->findAll();

        //Envoyer les stages à la vue chargée de les afficher
        return $this->render('prostages/index.html.twig',['stages'=>$stages]);
    }

    /**
     * @Route("/entreprises", name="prostages_entreprises")
     */
    public function afficherEntreprises(EntrepriseRepository $repoEntreprises): Response
    {
        //Récupération des entreprises, $repoEntreprises est directement récupéré
        //Grâce à l'injection de dépendances
        $entreprises = $repoEntreprises->findAll();

        return $this->render('prostages/affichageEntreprises.html.twig',['entreprises'=>$entreprises]);
    }

    /**
     * @Route("/formations", name="prostages_formations")
     */
    public function afficherFormations(FormationRepository $repoFormation): Response
    {
        //Récupération des formations, $repoFormation est directement récupéré
        //Grâce à l'injection de dépendances
        $formations = $repoFormation->findAll();

        return $this->render('prostages/affichageFormations.html.twig',['formations'=>$formations]);
    }

    /**
     * @Route("/stages/{id}", name="prostages_stage")
     */
    public function afficherStage(Stage $stage): Response
    {
        //Grâce à l'injection de dépendances, le stage cible est
        //directement récupéré avec l'id fourni en paramètre du lien
        return $this->render('prostages/affichageStage.html.twig',
      ['stage' => $stage]);
    }

    /**
      *@Route("/entreprises/{id}",name="prostages_stagesEntreprise")
      */
    public function afficherStagesEntreprise(Entreprise $entreprise, StageRepository $repoStage): Response
    {
      //Récupération des stages, $repoStage est directement récupéré
      //Grâce à l'injection de dépendances, ainsi que l'entreprise dont
      //l'id est fourni en paramètre de lien
      $stages = $repoStage->findBy(['entreprise'=>$entreprise]);

      return $this->render('prostages/affichageStagesEntreprise.html.twig',['entreprise'=>$entreprise,
                                                                     'stages'=>$stages]);
    }

    /**
      *@Route("/formations/{id}",name="prostages_stagesFormation")
      */
    public function afficherStagesFormation(Formation $formation): Response
    {
      //Grâce à l'injection de dépendances, nous avons récupéré la formation dont
      //l'id est fourni en paramètre de lien
      return $this->render('prostages/affichageStagesFormation.html.twig',['formation'=>$formation]);
    }
}
