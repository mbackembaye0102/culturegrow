<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CollaborateurController extends AbstractController
{
    /**
     * @Route("/collaborateur", name="collaborateur")
     */
    public function index()
    {
        return $this->render('collaborateur/index.html.twig', [
            'controller_name' => 'CollaborateurController',
        ]);
    }
}
