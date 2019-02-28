<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomeController extends Controller
{

    /**
     * @route("/", name="homepage")
     */

    public function home()
    {
        $prenom = ["Lior", "Joseph", "Anne"];
        return $this->render(
            'home.html.twig',
            [
                'title' => "Bonjour a tous",
                'age' => 31,
                'tableau' => $prenom
            ]
        );
    }
}


?>