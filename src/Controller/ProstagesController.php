<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Repository\EntrepriseRepository;
use App\Repository\StageRepository;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ObjectManager;
use App\Form\EntrepriseType;
use App\Form\StageType;

class ProstagesController extends AbstractController
{
    /**
     * @Route("/", name="prostages_accueil")
     */
    public function index(StageRepository $repoStage): Response
    {
        //Récupération des stages enregistrés, reposStage est directement récupéré
        //Grâce à l'injection de dépendances
        $stages = $repoStage->fetchAllStageFormationEntreprise();

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
        $entreprises = $repoEntreprises->fetchHaveStage();

        return $this->render('prostages/affichageEntreprises.html.twig',['entreprises'=>$entreprises]);
    }

    /**
     * @Route("/entreprises/ajouter", name="prostages_ajoutEntreprise")
     */
     public function ajouterEntreprise(Request $request, ObjectManager $manager, EntrepriseRepository $repoEntreprises)
     {
       $entreprise = new Entreprise();

       //Création du formulaire d'ajout d'une entreprise
       $form = $this->createForm(EntrepriseType::class, $entreprise);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
         //Enregistrement en BD
         $manager->persist($entreprise);
         $manager->flush();

         //Réinitialisation du formulaire en redigireant sur la même page
         return $this->redirectToRoute('prostages_ajoutEntreprise');
       }

       $entreprises = $repoEntreprises->findAll();

       return $this->render('prostages/ajoutModifEntreprise.html.twig',['formulaireEntreprise'=>$form->createView(),
                                                                        'action'=>'ajouter',
                                                                        'entreprises'=>$entreprises]);
     }

     /**
      * @Route("/entreprises/modifier/{id}", name="prostages_modifEntreprise")
      */
      public function modifierEntreprise(Entreprise $entreprise, Request $request, ObjectManager $manager, EntrepriseRepository $repoEntreprises)
      {
        //Création du formulaire de modification d'une entreprise
        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
          //Enregistrement en BD
          $manager->persist($entreprise);
          $manager->flush();

          //Retour à la page d'ajout d'une entreprise
          return $this->redirectToRoute('prostages_ajoutEntreprise');
        }

        $entreprises = $repoEntreprises->findAll();

        return $this->render('prostages/ajoutModifEntreprise.html.twig',['formulaireEntreprise'=>$form->createView(),
                                                                         'action'=>'modifier',
                                                                         'entreprises'=>$entreprises]);
      }

      /**
        *@Route("/entreprises/{nomEntreprise}",name="prostages_stagesEntreprise")
        */
      public function afficherStagesEntreprise($nomEntreprise, StageRepository $repoStage): Response
      {
        //Récupération des stages, $repoStage est directement récupéré
        //Grâce à l'injection de dépendances, ainsi que l'entreprise dont
        //l'id est fourni en paramètre de lien
        //$stages = $repoStage->findBy(['entreprise'=>$entreprise]);
        $stages = $repoStage->fetchByEntreprise($nomEntreprise);

        return $this->render('prostages/affichageStagesEntreprise.html.twig',['stages'=>$stages,
                                                                              'nomEntreprise'=>$nomEntreprise]);
      }

    /**
     * @Route("/formations", name="prostages_formations")
     */
    public function afficherFormations(FormationRepository $repoFormation): Response
    {
        //Récupération des formations, $repoFormation est directement récupéré
        //Grâce à l'injection de dépendances
        $formations = $repoFormation->fetchHaveStage();

        return $this->render('prostages/affichageFormations.html.twig',['formations'=>$formations]);
    }

    /**
     * @Route("/stages/ajouter", name="prostages_ajoutStage")
     */
    public function ajouterStage(Request $request, ObjectManager $manager): Response
    {
      $stage = new Stage();

      //Création du formulaire d'ajout d'une entreprise
      $form = $this->createForm(StageType::class, $stage);

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid())
      {
        //Enregistrement en BD
        $manager->persist($stage);
        $manager->flush();

        //Réinitialisation du formulaire en redigireant sur la même page
        return $this->redirectToRoute('prostages_ajoutStage');
      }

      return $this->render('prostages/ajoutStage.html.twig',['formulaireStage'=>$form->createView()]);
    }

    /**
     * @Route("/stages/{id}", name="prostages_stage")
     */
    public function afficherStage($id, StageRepository $repoStage): Response
    {
        $stage = $repoStage->fetchStageWithFormationEntreprise($id)[0];
        return $this->render('prostages/affichageStage.html.twig',['stage' => $stage]);
    }

    /**
      *@Route("/formations/{nomFormation}",name="prostages_stagesFormation")
      */
    public function afficherStagesFormation($nomFormation, StageRepository $repoStage): Response
    {
      //Grâce à l'injection de dépendances, nous avons récupéré la formation dont
      //l'id est fourni en paramètre de lien
      $stages = $repoStage->fetchByFormation($nomFormation);
      return $this->render('prostages/affichageStagesFormation.html.twig',['stages'=>$stages,
                                                                           'nomFormation'=>$nomFormation]);
    }
}
