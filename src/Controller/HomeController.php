<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{

/**
* @Route("/", name="homepage")
*/
  public function home(AdRepository $adRepo, UserRepository $userRepo) {

    return $this->render(
      'home.html.twig',
      [
        'ads' => $adRepo->findBestAds(3),
        'users' => $userRepo->findBestUsers(2)
      ]
    );
  }















/* Ci-dessous des exemples de routage lors du début de l'apprentissage de Symfony - Ils ne font pas partie de l'application */

/**
* @Route("/bonjour/{prenom}/age/{age}", name="hello")
* @Route("/salut", name="hello_base")
* @Route("/bonjour/{prenom}", name="hello_prenom")
* @Route("/bonjour/{prenom}/{age}")
* Montre la page qui dit bonjour
*/
public function hello($prenom = "anonyme", $age = 0) {
  return $this->render(
    'training/hello.html.twig',
    [
      'prenom' => $prenom,
      'age' => $age
    ]
  );
}


/**
* @Route("/learn", name="learn_page")
* Ceci est ma page d'apprentissage de symfony
*/
  public function learn() {
    $prenom = ["Pascal" => 47, "Marie-Pierre" => 51,"Marion" => 22, "Amélie" => 20];

    return $this->render(
      'training/learn.html.twig',
      [ 'title' => "Bonjour à tous et à toutes !",
        'age' => 10,
        'tableau' => $prenom
      ]
    );
  }
}
?>
