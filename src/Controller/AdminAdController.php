<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination)
    {
      /** Exemple avant pagination
        $limit = 10;

        $start = $page * $limit - $limit;

        $total = count($repo->findAll());

        $pages = ceil($total / $limit); // ceil est une fonction de php qui permet d'arrondir à l'entier supérieur, Ex 3.4 => 4

        return $this->render('admin/ad/index.html.twig', [
          // 'ads' => $repo->findAll() Permet d'afficher toutes les annonces
          'ads' => $repo->findBy([], [], $limit, $start),  // Permet de n'afficher que les annonces sélectionnées
          'pages' => $pages,
          'page' => $page
        ]);
        */

        $pagination->setEntityClass(Ad::Class)
                    ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
          // 'bookings' => $pagination->getData(),
          // 'pages' => $pagination->getPages(),
          // 'page' => $page
          // A la place de tout cela, nous pouvons écrire :
          'pagination' => $pagination
        ]);
    }

/**
 * Permet d'afficher le formulaire d'édition
 *
 * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
 * @param  Ad                     $ad      [description]
 * @param  Request                $request [description]
 * @param  EntityManagerInterface $manager [description]
 * @return Response                        [description]
 */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager) {
      $form = $this->createForm(AdType::Class, $ad);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $manager->persist($ad);
        $manager->flush();

        $this->addFlash(
          'success',
          "L'annonce <b>{$ad->getTitle()}</b> a bien été enregistrée !");
      }

      return $this->render('admin/ad/edit.html.twig', [
        'ad' => $ad,
        'form' => $form->createView()
      ]);
    }

/**
 * Permet de supprimer une annonce
 *
 * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
 *
 * @param  Ad                     $ad      [description]
 * @param  EntityManagerInterface $manager [description]
 * @return Response                          [description]
 */
    public function delete(Ad $ad, EntityManagerInterface $manager) {
      if(count($ad->getBookings()) > 0) {
        $this->addFlash(
          'warning',
          "Vous ne pouvez pas supprimmer l'annonce <b>{$ad->getTitle()}</b> car elle a des réservations en cours !"
        );
      } else {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
          'success',
          "L'annonce <b>{$ad->getTitle()}</b>a bien été supprimmée !"
        );
      }

      return $this->redirectToRoute('admin_ads_index');
    }
}
