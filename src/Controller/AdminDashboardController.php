<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController {

    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService) {

      // $users    = $statsService->getUsersCount();
      // $ads      = $statsService->getAdsCount();
      // $bookings = $statsService->getBookingsCount();
      // $comments = $statsService->getCommentsCount();
      // A la place du code ci-dessus, on crée une fonction getStats() dans le StatsService

      $stats    = $statsService->getStats();
      $bestAds  = $statsService->getAdsStats('DESC');
      $worstAds = $statsService->getAdsStats('ASC');

    return $this->render('admin/dashboard/index.html.twig', [
        /* La fonction compact() de PHP permet de créer un tableau automatiquement en nommant les clés. La fonction recherche une variable avec le même nom et l'ajoute dans le tableau.
        Ainsi, au lieu du code suivant :
        'stats' => [
          'users' => $users,
          'ads' => $ads,
          'bookings' => $bookings,
          'comments' => $comments
          ]
          Nous pouvons écire :
        'stats' => compact('users', 'ads', 'bookings', 'comments'),*/
        'stats' => $stats,
        'bestAds' => $bestAds,
        'worstAds' => $worstAds
    ]);
  }
}
