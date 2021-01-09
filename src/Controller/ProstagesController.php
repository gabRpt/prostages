<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProstagesController extends AbstractController
{
    /**
     * @Route("/", name="prostages_accueil")
     */
    public function index(): Response
    {
        return $this->render('prostages/index.html.twig');
    }

    /**
     * @Route("/stages/345", name="prostages_stage345")
     */
    public function afficherStage(): Response
    {
        return $this->render('prostages/affichageStage.html.twig',
      ['idStage' => 250]);
    }
}
