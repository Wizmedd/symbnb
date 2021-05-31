<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


//grace à l'heritage d'AbstractController on peut utiliser la fonction render()

class HomeController extends AbstractController
{

    public function index()
    {
        $prenoms = ['Mehdi' => 36, 'Amira' => 16, 'Ahmed' => 60];

        // dd("test sans response");
        return $this->render(
            'home.html.twig',
            [
                'title' => "titre definit en parametre dans le render",
                'age' => "36",
                'tableau' => $prenoms
            ]
        );
    }

    /**
     * @Route("/hello/{surname}", name="hello" )
     * exemple route parametré 
     * on peut ajouter plusieurs route et le typer
     */
    public function hello($surname = "anonyme")
    {

        return $this->render(
            'hello.html.twig',
            [
                'title' => "titre défini  dans le render de hello",
                'age' => "36",
                'tableau' => $surname
            ]
        );
    }
}
