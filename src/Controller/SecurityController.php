<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/inscription", name="app_inscription")
     */
     public function inscription(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
     {
       $user = new User();

       //Création du formulaire d'ajout d'une entreprise
       $form = $this->createForm(UserType::class, $user);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
         //role user
         $user->setRoles(['ROLE_USER']);

         //encodage mdp
         $encoded = $encoder->encodePassword($user, $user->getPassword());
         $user->setPassword($encoded);

         //Enregistrement en BD
         $manager->persist($user);
         $manager->flush();

         //Réinitialisation du formulaire en redigireant sur la même page
         return $this->redirectToRoute('app_login');
       }

       return $this->render('security/inscription.html.twig',['formulaireUser'=>$form->createView()]);
     }
}
