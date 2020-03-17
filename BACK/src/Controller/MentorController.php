<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MentorController extends AbstractController
{
    /**
     * @Route("/mentor", name="mentor")
     */
    public function index()
    {
        return $this->render('mentor/index.html.twig', [
            'controller_name' => 'MentorController',
        ]);
    }
}
