<?php

namespace App\Controller;

use App\Entity\UserTeamPromo;
use App\Repository\AllsessionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SystemeController extends AbstractController
{
    /**
     * @Route("/infos123", name="systeme")
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
 /**
     * @Route("/infos")
     */
    public function detailuser(Request $request, UserRepository $userRepository,AllsessionRepository $allsessionRepository)
    {
        $user=$this->getUser();
        $date=date('Y-m-d');
        
        if ($user) {
            $team = $this->getDoctrine()->getRepository(UserTeamPromo::class)->findBy(['user' => $user->getId()]);
            $structure=$team[0]->getTeamPromo()->getStructure();
            $session=$allsessionRepository->findOneBy([
                'date'=>$date,
                'structure'=>$structure->getId()
            ]);
            if (!$session) {
                $tableau = [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'prenom' => $user->getPrenom(),
                    'nom' => $user->getNom(),
                    'statut' => $user->getStatut(),
                    'telephone' => $user->getTelephone(),
                    'poste' => $user->getPoste(),
                    'image' => $user->getImage(),
                    'evaluation'=>'pas d evaluation pour la structure',
                ];
                return $this->json($tableau);
            }
            
            $a = [];
            $isevaluer=array();
            $teamsession=$session->getTeams();
            $userteam=[];
            for ($i=0; $i <count($team) ; $i++) { 
                array_push($userteam,$team[$i]->getTeamPromo()->getNom());
                array_push($a,$team[$i]->getTeamPromo()->getNom());
            }
            if ($session->getConcerner()==="good") {
                if (count($teamsession)!=0) {
                    for ($i=0; $i < count($teamsession); $i++) { 
                        if (!in_array($teamsession[$i],$userteam)) {
                            array_push($userteam,$teamsession[$i]);
                        }
                    }
                }
            }
            elseif($session->getConcerner()==="bad"){
                for ($i=0; $i <count($teamsession) ; $i++) { 
                    if (in_array($teamsession[$i],$userteam)) {
                        array_push($isevaluer,$teamsession[$i]);
                    }
                    $userteam=$isevaluer;
                }
            }
            if (count($userteam)==0) {
                $userteam="rien";
            }
            $tableau = [
                'id' => $user->getStructure()->getId(),
                'iduser'=>$user->getId(),
                'username' => $user->getUsername(),
                'prenom' => $user->getPrenom(),
                'nom' => $user->getNom(),
                'statut' => $user->getStatut(),
                'telephone' => $user->getTelephone(),
                'poste' => $user->getPoste(),
                'image' => $user->getImage(),
                'team' => $a,
                'evaluation'=>$session->getStatut(),
                'all'=>$session->getConcerner(),
                'teamevaluer'=>$userteam,
            ];
            return $this->json($tableau);
        }
    }
}
