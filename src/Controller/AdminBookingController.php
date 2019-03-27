<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\PaginationService;

class AdminBookingController extends AbstractController
{
    /**
     * permet d'afficher la liste des réservations
     * 
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     *
     * @param BookingRepository $repo
     * @return Response
     */
    public function index(BookingRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
                   ->setPage($page);
                  
                   
           $bookings = $pagination->getData();
          

        //$limit = 10;
        //$start = $page * $limit - $limit;
        // 1 * 10 = 10 - 10 = 0
        // 2 * 10 = 20 - 10 = 10

        //$total = count($repo->findAll());

        //$pages = ceil($total / 10);

        //$repo = $this->getDoctrine()->getRepository(Booking::class);
        //comments = $repo->findAll();

        return $this->render('admin/booking/index.html.twig', [
            //'bookings' => $pagination->getData(),
            //'pages' => $pagination->getPages(),
            //'page' =>$page
            'pagination' => $pagination
        ]);
    }
    

    /**
     * Permet d'éditer une réservation
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking, Request $request,ObjectManager $manager) {
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation numéro {$booking->getId()} a bien été modifiée !"
            );
            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render('admin/booking/edit.html.twig',[
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }


    /**
     * Permet de supprimer une réservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     * 
     * @param Booking $booking
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager) {
        $manager->remove($booking);
        $manager->flush();

    $this->addFlash(
        'success',
        "La réservation a bien été supprimée!"
    );
    return $this->redirectToRoute("admin_booking_index");
    }
}
