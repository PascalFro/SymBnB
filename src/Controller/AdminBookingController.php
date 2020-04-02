<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * Permet l'affichage des réservations
     *
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_bookings_index")
     *
     * @param  BookingRepository $repo       [description]
     * @param  [type]            $page       [description]
     * @param  PaginationService $pagination [description]
     * @return [type]                        [description]
     */
    public function index(BookingRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::Class)
                    ->setPage($page);

        return $this->render('admin/booking/index.html.twig', [
          // 'bookings' => $pagination->getData(),
          // 'pages' => $pagination->getPages(),
          // 'page' => $page
          // A la place de toutcela, nous pouvons écrire :
          'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'éditer une réservation
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     *
     * @param  Booking                $booking [description]
     * @param  Request                $request [description]
     * @param  EntityManagerInterface $manager [description]
     * @return Response                        [description]
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager) {

      $form = $this->createForm(AdminBookingType::Class, $booking);

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()) {
        $booking->setAmount(0);

        $manager->persist($booking);
        $manager->flush();

        $this->addFlash(
          'success',
          "La réservation de l'annonce n° {{ booking.id }} a bien été modifée !"
        );
        return $this->redirectToRoute("admin_bookings_index");
      }

      return $this->render('admin/booking/edit.html.twig', [
        'form' => $form->createView(),
        'booking' => $booking
      ]);

    }

    /**
     * Permet de supprimer une réservation
     *
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     *
     * @param  Booking                $booking [description]
     * @param  EntityManagerInterface $manager [description]
     * @return Response                          [description]
     */
    public function delete( Booking $booking, EntityManagerInterface $manager) {
      $manager->remove($booking);
      $manager->flush();

      $this->addFlash(
        'success',
        "La suppression de l'annonce à bien été effectuée"
      );

      return $this->redirectToRoute("admin_bookings_index");
    }
}
