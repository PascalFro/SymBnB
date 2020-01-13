<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

/**
* @Route("/bonjour/{prenom}/age/{age}", name="hello")
* @Route("/salut", name="hello_base")
* @Route("/bonjour/{prenom}", name="hello_prenom")
* @Route("/bonjour/{prenom}/{age}")
* Montre la page qui dit bonjour
*/
public function hello($prenom = "anonyme", $age = 0) {
  return $this->render(
    'hello.html.twig',
    [
      'prenom' => $prenom,
      'age' => $age
    ]
  );
}
/**
* @Route("/", name="homepage")
*/
  public function home() {
    $prenom = ["Pascal" => 47, "Marie-Pierre" => 51,"Marion" => 22, "Amélie" => 20];

    return $this->render(
      'home.html.twig',
      [ 'title' => "Bonjour à tous et à toutes !",
        'age' => 10,
        'tableau' => $prenom
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
      'learn.html.twig',
      [ 'title' => "Bonjour à tous et à toutes !",
        'age' => 10,
        'tableau' => $prenom
      ]
    );
  }
}
?>
