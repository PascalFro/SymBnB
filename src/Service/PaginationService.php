<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService {
  private $entityClass;
  private $limit = 10;
  private $currentPage = 1;
  private $manager;
  private $twig;
  private $route;
  private $templatePath;

  public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath) {
    $this->route   = $request->getCurrentRequest()->attributes->get('_route');
    $this->manager = $manager;
    $this->twig    = $twig;
    $this->templatePath = $templatePath;
  }

  public function setTemplatePath($templatePath) {
    $this->templatePath = $templatePath;
    return $this;
  }

  public function getTemplatePath() {
    return $this->templatePath;
  }

  public function setRoute($route) {
    $this->route   = $route;
  }

  public function getRoute() {
    return $this->route;
  }

  public function display() {
    $this->twig->display($this->templatePath, [
      'page'  => $this->currentPage,
      'pages' => $this->getPages(),
      'route' => $this->route
    ]);
  }

  public function getPages() {
    if(empty($this->entityClass)) {
      throw new Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet PaginationService !", 1);
    }
    // 1° Connaitre le total des enregistrements de la table
    $repo = $this->manager->getRepository($this->entityClass);
    $total = count($repo->findAll());

    // 2° Faire la division, l'arrondi et le renvoyer
    $pages = ceil($total /  $this->limit);

    return $pages;
  }

  public function getData() {

    if(empty($this->entityClass)) {
      throw new Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet PaginationService !", 1);

    }
    // 1° Calculer l'offset (le début)
    // Pour aller à la page 1 : 1 * 10 = 10 - 10 = 0
    // Pour aller à la page 2 : 2 * 10 = 20 - 10 = 10
    $offset = $this->currentPage * $this->limit - $this->limit;

    // 2° Demander au repository de trouver les éléments
    $repo = $this->manager->getRepository($this->entityClass);
    $data = $repo->findBy([], [], $this->limit, $offset);
    // 3° Renvoyer les éléments en question
    return $data;
  }

  public function setEntityClass($entityClass) {
    $this->entityClass = $entityClass;

    return $this;
  }

  public function getEntityClass() {
    return $this->entityClass;
  }

  public function setLimit($limit) {
    $this->limit = $limit;

    return $this;
  }

  public function getLimit() {
    return $this->limit;
  }

  public function setPage($page) {
    $this->currentPage = $page;

    return $this;
  }
  public function getPage() {
    return $this->currentPage;
  }
}
