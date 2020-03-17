<?php

namespace App\Controller;

use App\Repository\StructureRepository;
use App\Repository\TeamPromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SystemeController extends AbstractController
{
    /**
     * @Route("/infos", name="systeme")
     */
    public function infos(TeamPromoRepository $teamPromoRepository,StructureRepository $structureRepository){
       $a=$this->getUser();
      //$a=$teamPromoRepository->find(1);
     //$a=$structureRepository->find(1);
dump($a);die();
    //  return new JsonResponse($a->getPrenom());
    }

}
