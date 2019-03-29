<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\AdRepository;
use App\Repository\UserRepository;


class HomeController extends Controller
{

    /**
     * @route("/", name="homepage")
     */

    public function home(AdRepository $adRepo, UserRepository $userRepo)
    {
        
        return $this->render(
            'home.html.twig',
            [
                'ads' => $adRepo->findBestAds(3),
                'users' => $userRepo->findBestUsers(2)
            ]
        );
    }
}


?>