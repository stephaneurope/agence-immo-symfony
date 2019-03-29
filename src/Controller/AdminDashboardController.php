<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\StatsService;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager, StatsService $statsService)
    {   
        //$users    = $statsService->getUsersCount();
        //$ads      = $statsService->getAdsCount();
        //$bookings = $statsService->getBookingsCount();
        //$comments = $statsService->getCommentsCount();

        $stats    = $statsService->getStats();

        $bestAds  = $statsService->getAdsStats('DESC');

        $worstAds = $statsService->getAdsStats('ASC');
        
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds,
            /* [
                'users' => $users,
                'ads' => $ads,
                'bookings' =>$bookings,
                'comments' => $comments
            ]*/
        ]);
    }
}
