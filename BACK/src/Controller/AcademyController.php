<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AcademyController extends AbstractController
{
    /**
     * @Route("/academy", name="academy")
     */
    public function index()
    {
        return $this->render('academy/index.html.twig', [
            'controller_name' => 'AcademyController',
        ]);
    }
}
