<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;


class AdController extends AbstractController {
    /**
     * @Route("/ads", name="ads_index")
     */

    public function index(AdRepository $repo)
    {
        // AdRepository $repo est une injection de dépendance, et évite le code suivant :
        // $repo = $this->getDoctrine()->getRepository(Ad::class)

        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }


    /**
     * Permet d'afficher une seule annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */

    /*
    public function show($slug, AdRepository $repo) {
        // Je récupère l'annonce qui correspond au slug
        $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
            ]);
    }

    * On peut templacer cette fonction, en utilsiant le ParamConverter de Symfony, par cette fonction-ci :
    */

    public function show(Ad $ad) {
        // On dit à Symfony qu'on a besoin d'une annonce (ad) de la classe Annonce (Ad)
        // on récupère l'annonce qui correpsond au slug de la route
        // Symfony convertit le slug en annonce
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
            ]);
    }

    /**
     * Permet de créer une annonce
     *
     * @Route("/ads/new", name="ads_create")
     *
     * @return Response
     */

        public function create() {

        }
}
