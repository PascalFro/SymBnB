<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comments_index")
     */
    public function index(CommentRepository $repo)
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll()
        ]);
    }

  /**
   * Permet d'afficher le formulaire d'édition d'un commentaire
   *
   * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
   *
   * @param  Comment                $comment [description]
   * @param  Request                $request [description]
   * @param  EntityManagerInterface $manager [description]
   * @return Response                        [description]
   */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager) {
        $form = $this->createForm(AdminCommentType::Class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
          $manager->persist($comment);
          $manager->flush();

          $this->addFlash(
            'success',
            "Le commentaire {$comment->getId()} a bien été modifié !"
          );
        }

        return $this->render('admin/comment/edit.html.twig', [
          'comment' => $comment,
          'form' => $form->createView()
        ]);

    }


  /**
   * Permet de supprimmer un commentaire
   *
   * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
   *
   * @param  Comment                $comment [description]
   * @param  EntityManagerInterface $manager [description]
   * @return Response                        [description]
   */
    public function delete(Comment $comment, EntityManagerInterface $manager) {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
          'success',
          "Le commentaire de {$comment->getAuthor()->getFullName()} a bien été supprimmé !");

        return $this->redirectToRoute('admin_comments_index');
    }
}
