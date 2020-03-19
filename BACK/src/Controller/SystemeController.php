<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserTeamPromo;
use App\Repository\PosteRepository;
use App\Repository\StructureRepository;
use App\Repository\TeamPromoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/yaya")
 */
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
/**
     * @Route("/saveusergrow", methods={"POST"})
     */
    public function adduser(Request $request,UserPasswordEncoderInterface $encoder,EntityManagerInterface $entityManagerInterface,TeamPromoRepository $teamPromoRepository){
        $data= $request->request->all();
        $user= new User();
        $taille=$data["taille"];
        $team=[];
        for ($i=1; $i <=$taille; $i++) { 
            $teams[$i]=$teamPromoRepository->findOneBy(['nom'=>$data["team$i"]]);
            array_push($team,$teams[$i]);
        }
        $user->setPrenom($data['prenom']);
        $user->setNom($data['nom']);
        $user->setUsername($data['email']);
        $user->setPoste($data['poste']);
        $user->setPassword($encoder->encodePassword($user,"welcome"));
        $profil=$data['profil'];
        $user->setRoles(["ROLE_$profil"]);
        $user->setTelephone($data['telephone']);
        $user->setStatut("actif");
        $entityManagerInterface->persist($user);
        
        for ($i=0; $i <$taille; $i++) { 
            $userTeamPromo= new UserTeamPromo();
            $userTeamPromo->setUser($user);
            $userTeamPromo->setTeamPromo($team[$i]);
            $entityManagerInterface->persist($userTeamPromo);
        }
        $entityManagerInterface->flush();
        return $this->json([
            'message'=>'Ajout Effectuer',
            'status'=>200
        ]);  
    }
        /**
     * @Route("/growposte")
     */
    public function growposte(PosteRepository $posteTeam,SerializerInterface $serializer){
        $team=$posteTeam->findAll();
        $data = $serializer->serialize($team, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/listuser")
     */
    public function listuser(Request $request,UserRepository $user,SerializerInterface $serializer){
        $a=$user->findAll();
        $data = $serializer->serialize($a, 'json',[
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
