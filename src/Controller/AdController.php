<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
     * Permet de créer une annonce
     *
     * @Route("/ads/new", name="ads_create")
     *
     * @return Response
     */

    public function create(Request $request, EntityManagerInterface $manager) {

        $ad = new Ad();

        /*
        La propriété request de la variable $request représente le POST
        Ex: $request->request->get('title') nous donne l'information qu'il y a dasn le champ titre de notre formulaire
         */


        /*
        $form est un très gros Objet qui contient plein de méthodes

        $form = $this->createFormBuilder($ad)
                    ->add('title')
                    ->add('introduction')
                    ->add('content')
                    ->add('rooms')
                    ->add('price')
                    ->add('coverImage')
                    ->add('save', SubmitType::class, [
                        'label' => 'Créer la nouvelle annonce',
                        'attr' => [
                            'class' => 'btn btn-primary']
                    ])
                    ->getForm();
        */

        /* On a créer à la place de tout ce code trop volumineux pour un controller, la classe form, qui va nous permettre d'instancier un form externe avec la fonction createForm qui permet de créer un formulaire externe */

        $form = $this->createForm(AdType::Class, $ad);

        $form->handleRequest($request);
        /*
        La fonction handleRequest() permet de parcourir la requête et d'extraire les informations du formulaire.
        Le formulaire va analyser la requête et il va retrouver tous les champs qu'il y a dans le formulaire, il va ensuite relier toutes les informations trouvées dans le formulaire à la variable qui se trouve dans la fonction de création du formulaire (dans notre exemple, c'est $ad, il va ensuite les placer dans l'entité concernée ($ad ici))
         */


        if($form->isSubmitted() && $form->isValid()) {

            // $manager = $this->getDoctrine()->getManager();
            /* manager est une dépendance de la fonction, c'est à dire qu'elle ne peut pas fonctionner si je n'ai pas le Manager, si je n'ai pas cette 'dépendance'. Donc qui dit dépendance, dit injection de dépendance ! On va donc mettre en paramètre dans la fonction create() la classe "EntityManagerInterface $manager"
            */

            /*
            La fonction isSubmitted() permet de savoir si le formulaire a été soumis ou pas.
            La fonction isValid() permet de savoir si le formulaire est valide par rapport aux règles en place.
             */

            /*
            Le fait d'ajouter des images dans l'entité "ad" pose problème car les images sont dans l'entité 'images', il faut donc les enregistrer dans leur base en même temps
             */
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();
            /*
            Rappel : Le manager de Doctrine, c'est lui qui manipule les enregistrements en base de données
            persist() prévient Doctrine qu'on veut sauver
            flush() envoie la requête SQL
             */
            $this->addFlash(
            'success',
            "L'annonce <b>{$ad->getTitle()}</b> a bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show', [
             'slug' => $ad->getSlug()
            ]);
            /*

             */
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("ads/{slug}/edit", name="ads-edit")
     *
     * @return Response [description]
     */

       public function edit(Ad $ad, Request $request, EntityManagerInterface $manager) {

        $form = $this->createForm(AdType::Class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            /*
            Rappel : Le manager de Doctrine, c'est lui qui manipule les enregistrements en base de données
            persist() prévient Doctrine qu'on veut sauver
            flush() envoie la requête SQL
             */
            $this->addFlash(
            'success',
            "Les modification de l'annonce <b>{$ad->getTitle()}</b> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('ads_show', [
             'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
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

}
