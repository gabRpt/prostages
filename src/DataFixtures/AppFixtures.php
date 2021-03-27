<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Formation;
use App\Entity\Entreprise;
use App\Entity\Stage;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Création des utilisateurs de test
        $gabin = new User();
        $gabin->setPrenom("Gabin");
        $gabin->setNom("Raapoto");
        $gabin->setEmail("gabin@gmail.com");
        $gabin->setRoles(["ROLE_USER","ROLE_ADMIN"]);
        $gabin->setPassword('$2y$10$Uvhkg6KD3l6QkXDPRSnnWODjvOmL0PZycm.YkyLQDmceplOm6p3GO');
        $manager->persist($gabin);

        $gabdeux = new User();
        $gabdeux->setPrenom("GabDeux");
        $gabdeux->setNom("Otopaar");
        $gabdeux->setEmail("gabdeux@gmail.com");
        $gabdeux->setRoles(["ROLE_USER"]);
        $gabdeux->setPassword('$2y$10$wztHAUeD9pvBDEzOxQKTeuRWpQ7IcmmKdJZ5YmkCfM1jyiyOEy3n6');
        $manager->persist($gabdeux);

        //---Création des formations---
        $dutInfo = new Formation();
        $dutInfo->setNom("DUT Informatique");
        $dutInfo->setDescription("Le DUT Informatique a pour but de former des informaticiens généralistes de niveau Bac+2 au travers d'une solide formation théorique et pratique.");

        $duTic = new Formation();
        $duTic->setNom("DU TIC");
        $duTic->setDescription("Le DU TIC - Technologies de l’information et de la communication - s’adresse à toute personne en reconversion professionnelle ou en recherche de compléments de compétences dans les domaines du web, du multimédia, du webmarketing et de la gestion de projets.");

        $lpMedia = new Formation();
        $lpMedia->setNom("LP Multimédia");
        $lpMedia->setDescription("La licence professionnelle Métiers du numérique : conception, rédaction, réalisation web, de niveau Bac+3, a pour but de doter les candidats de compétences croisées dans les domaines de l'informatique, du multimédia et de la gestion de projet.");

        $tabFormations = array($dutInfo,$duTic,$lpMedia); //Tableau contenant toutes les formations

        //On signale à Doctrine que nous avons créer des formations
        $manager->persist($dutInfo);
        $manager->persist($duTic);
        $manager->persist($lpMedia);

        //Création d'un générateur de données Faker avec des données françaises
        $faker = \Faker\Factory::create('fr_FR');
        //---Création des entreprises
        for ($numEntreprise=0 ; $numEntreprise < 10 ; $numEntreprise++){
          $entreprise = new Entreprise();
          $entreprise->setNom($faker->company); //nom aléatoire
          $entreprise->setActivite($faker->catchPhrase); //activite aléatoire
          $entreprise->setAdresse($faker->address()); //adresse aléatoire
          $entreprise->setSite($faker->url); //url aléatoire

          //On signale à Doctrine que nous avons créer une entreprise
          $manager->persist($entreprise);

          //On génère un nombre aléatoire entre 0 et 4 correspondant au nombre de stages à générer
          $nbStageAGenerer = $faker->numberBetween($min=0, $max=4);

          //On génère les stages proposés par l'entreprise
          for($numStage=0 ; $numStage < $nbStageAGenerer ; $numStage++)
          {
            $stage = new Stage();
            $stage->setIntitule($faker->jobTitle); //Intitule aléatoire
            $stage->setMission($faker->realText($maxNbChars = 200, $indexSize = 2)); //description aléatoire de 200 charactères max
            $stage->setMail($faker->companyEmail); //mail aléatoire

            $stage->setEntreprise($entreprise); // On attribut l'entreprise courante au stage
            $entreprise->addStage($stage); //On ajoute le stage à la collection de stage de l'entreprise

            //On signale à Doctrine que nous avons modifier l'entreprise
            $manager->persist($entreprise);

            //Nombre de formations à affecter au stage min 1 max 3
            $nbFormations = $faker->numberBetween($min=1, $max=3);
            $dernierNum=-1; //Dernière formation ajoutée
            //Affectation de formation(s) au stage parmis les formations que nous avons précédemment enregister dans tabFormations
            for($i=0 ; $i<$nbFormations ; $i++)
            {
              //indice de la formation aléatoire à ajouter
              $numFormation = $faker->numberBetween($min=0, $max=2);
              //Si on l'a pas déjà ajoutée on l'ajoute
              if ($numFormation != $dernierNum)
              {
                $stage->addFormation($tabFormations[$numFormation]); //On ajoute la formation à la Collection de formation de stages
                $tabFormations[$numFormation]->addStage($stage); //On ajoute le stage à la Collection de stages de formation
                $dernierNum=$numFormation;

                //On signale à Doctrine que nous avons modifier des formations et le stage
                $manager->persist($stage);
                $manager->persist($tabFormations[$numFormation]);
              }
            }
          }
        }

        $manager->flush();
    }
}
