<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/')]
    public function homepage()
    {
        return $this->render('homepage.html.twig');
    }

    #[Route('/browse')]
    public function browse()
    {
        return $this->render('browse.html.twig');
    }
}
