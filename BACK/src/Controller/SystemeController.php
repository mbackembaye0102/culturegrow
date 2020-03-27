<?php

namespace App\Controller;

use App\Entity\UserTeamPromo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SystemeController extends AbstractController
{
    /**
     * @Route("/infos", name="systeme")
     */
    public function infos(){
       $user=$this->getUser();
       $team=$this->getDoctrine()->getRepository(UserTeamPromo::class)->findBy(['user'=>$user->getId()]);
       $a="";
        for ($i=0; $i <count($team) ; $i++) {
            if ($i==0) {
                $a=$a.$team[$i]->getTeamPromo()->getNom();
            }
            else{
                $a=$a."&".$team[$i]->getTeamPromo()->getNom();
            }
            
        }
       $tableau=[
         'id'=>$user->getId(),
         'username'=>$user->getUsername(),
         'prenom'=>$user->getPrenom(),
         'nom'=>$user->getNom(),
         'statut'=>$user->getStatut(),
         'telephone'=>$user->getTelephone(),
         'poste'=>$user->getPoste(),
         'image'=>$user->getImage(),
         'team'=>$a
       ];
       return $this->json($tableau);

    }

}
