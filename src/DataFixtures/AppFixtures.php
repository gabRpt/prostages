<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Formation;
use App\Entity\Entreprise;
use App\Entity\Stage;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
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

        $tabFormations = array($dutInfo,$duTic,$lpMedia);
        $manager->persist($dutInfo);
        $manager->persist($duTic);
        $manager->persist($lpMedia);

        //Création d'un générateur de données Faker
        $faker = \Faker\Factory::create('fr_FR');
        //---Création des entreprises
        for ($numEntreprise=0 ; $numEntreprise < 10 ; $numEntreprise++){
          $entreprise = new Entreprise();
          $entreprise->setNom($faker->company);
          $entreprise->setActivite($faker->catchPhrase);
          $entreprise->setAdresse($faker->address());
          $entreprise->setSite($faker->url);
          $manager->persist($entreprise);

          $nbStageAGenerer = $faker->numberBetween($min=0, $max=4);
          for($numStage=0 ; $numStage < $nbStageAGenerer ; $numStage++)
          {
            $stage = new Stage();
            $stage->setIntitule($faker->jobTitle);
            $stage->setMission($faker->realText($maxNbChars = 200, $indexSize = 2));
            $stage->setMail($faker->companyEmail);
            $stage->setEntreprise($entreprise);
            $entreprise->addStage($stage);
            $manager->persist($entreprise);

            //Nombre de formations à affecter au stage min 1 max 3
            $nbFormations = $faker->numberBetween($min=1, $max=3);
            $dernierNum=-1; //Dernière formation ajoutée
            for($i=0 ; $i<$nbFormations ; $i++)
            {
              //Formation aléatoire à ajouter
              $numFormation = $faker->numberBetween($min=0, $max=2);
              //Si on l'a pas déjà ajoutée on l'ajoute
              if ($numFormation != $dernierNum)
              {
                $stage->addFormation($tabFormations[$numFormation]);
                $tabFormations[$numFormation]->addStage($stage);
                $dernierNum=$numFormation;

                $manager->persist($stage);
                $manager->persist($tabFormations[$numFormation]);
              }
            }
          }
        }

        $manager->flush();
    }
}
