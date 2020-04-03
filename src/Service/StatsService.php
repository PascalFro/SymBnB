<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService {
  private $manager;

  public function __construct(EntityManagerInterface $manager) {
    $this->manager = $manager;
  }

  public function getStats() {
    $users    = $this->getUsersCount();
    $ads      = $this->getAdsCount();
    $bookings = $this->getBookingsCount();
    $comments = $this->getCommentsCount();

    return compact('users', 'ads', 'bookings', 'comments');
  }

  public function getUsersCount() {
    return $this->manager->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();
  }

  public function getAdsCount() {
    return $this->manager->createQuery('SELECT count(a) FROM App\Entity\Ad a')->getSingleScalarResult();
  }

  public function getBookingsCount() {
    return $this->manager->createQuery('SELECT count(b) FROM App\Entity\Booking b')->getSingleScalarResult();
  }

  public function getCommentsCount() {
    return $this->manager->createQuery('SELECT count(c) FROM App\Entity\Comment c')->getSingleScalarResult();
  }

  public function getAdsStats($direction) {
    return $this->manager->createQuery(
        'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
        FROM App\Entity\Comment c
        JOIN c.ad a
        JOIN a.author u
        GROUP BY a
        ORDER BY note ' . $direction
      )
      ->setMaxResults(5)
      ->getResult();
  }
  /* La fonction getAdsStats() vient remplacer les fonctions getBestAds() et getWorstAds() car il n'y avait que l'ordre qui différait */
/*
  public function getBestAds() {
    return $this->manager->createQuery(
        'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
        FROM App\Entity\Comment c
        JOIN c.ad a
        JOIN a.author u
        GROUP BY a
        ORDER BY note . $direction'
      )
      // a représente la table ad puisque dans la table des comments, il y a un champs ad qui est une relation avec la table ad
      // u représente la table user puisque dans la table des comments, il y a un champs author qui renvoie vers la table user
      // Groupé par annonce
      // Trié par note de la plus grande à la plus petite

      ->setMaxResults(5)
      ->getResult();
  }

  public function getWorstAds() {
    return $this->$manager->createQuery(
        'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
        FROM App\Entity\Comment c
        JOIN c.ad a
        JOIN a.author u
        GROUP BY a
        ORDER BY note ASC'
      )
      // a représente la table ad puisque dans la table des comments, il y a un champs ad qui est une relation avec la table ad
      // u représente la table user puisque dans la table des comments, il y a un champs author qui renvoie vers la table user
      // Groupé par annonce
      // Trié par note de la plus grande à la plus petite

      ->setMaxResults(5)
      ->getResult();
  } */
}
