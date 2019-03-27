<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PaginationService;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination)
    {   
        $pagination->setEntityClass(Ad::class)
                   ->setPage($page);
                  
                   
           $ads = $pagination->getData();

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    
    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad,Request $request, ObjectManager $manager) {
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    
    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/admin/ads/{id}/delete" , name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager) 
    {
        if(count($ad->getBookings())> 0) {
            $this->addFlash(
               'warning' ,
               "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède deja des reservations"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();

       $this->addFlash(
             'success',
             "L'annonce <strong>{$ad->getTitle()}</strong> a bien été suprimée !"
       );
    }
     return $this->redirectToRoute('admin_ads_index');
    }

}
