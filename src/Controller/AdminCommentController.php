<?php

namespace App\Controller;

use App\Entity\Comment;

use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * Permet d'afficher la liste des commentaires
     * 
     * @Route("/admin/comments", name="admin_comment_index")
     *
     * @param CommentRepository $repo
     * @return Response
     */
    public function index(CommentRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Comment::class);
        //comments = $repo->findAll();
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll(),
        ]);
    }

    /**
     * Permet de modifier les commentaires
     * 
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     *
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment,Request $request, ObjectManager $manager ) {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire  a bien été modifié !"
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment ,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Comment $comment, ObjectManager $manager) {
        
            $manager->remove($comment);
            $manager->flush();

       $this->addFlash(
             'success',
             "Le commentaire de {$comment->getAuthor()->getFullName()} a bien été suprimé !"
       );
       return $this->redirectToRoute('admin_comment_index');
    }
}
