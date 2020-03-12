<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\createForm;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gére le formulaire de connexion
     * @Route("/login", name="account_login")
     * @return  Response
     */
    public function login(AuthenticationUtils $utils)
    {
      $error = $utils->getLastAuthenticationError();
      $username = $utils->getLastUsername();

      return $this->render('account/login.html.twig', [
        'hasError' => $error !== null,
        'username' =>$username
      ]);

    }

    /**
    * Permet de se déconnecter
    *
    * @Route("/logout", name="account_logout")
    *
    * @return void
    */
    public function logout() {
     // .. rien !
    }

    /**
     * Permet d'afficher le formulaire d'inscription
     *
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
      $user = new User();

      $form = $this->createForm(RegistrationType::Class, $user);

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()) {
        $hash = $encoder->encodePassword($user,  $user->getHash());
        // encodePassword() permet d'encoder un password avec l'algotithme décrit dans les encoders
        $user->setHash($hash);

        $manager->persist($user); // Préveint Doctrine qu'on veut sauvegarder les infos
        $manager->flush(); // Envoie la requête

        $this->addFlash(
            'success' , "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter.");

        return $this->redirectToRoute('account_login');
      }

      return $this->render('account/registration.html.twig', [
        'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher et de traiter le formulaire de modification de profil
     *
     * @Route("/account/profile", name="account_profile")
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager) {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::Class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications du profil ont été enregristrées avec succès !"
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/account/password-update", name="account_password")
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager) {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::Class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // 1 - Vérifier que le oldPassword soir le même que dle password du user
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                // Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success', "Votre mot de passe a bine été modifé !");

                return $this->redirectToRoute('homepage');
            }

        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     *@Route("/account", name="account_index")
     *
     * @return Response
     */
    public function myAccount() {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()]);
    }
}

