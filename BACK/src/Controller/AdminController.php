<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Entity\TeamPromo;
use App\Entity\User;
use App\Repository\StructureRepository;
use App\Repository\TeamPromoRepository;
use App\Repository\UserRepository;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
     * @Route("/admin", name="admin")
     */
class AdminController extends AbstractController
{
    /**
     * @Route("/saveusergrow", methods={"POST"})
     */
    public function adduser(Request $request,UserPasswordEncoderInterface $encoder,EntityManagerInterface $entityManagerInterface){
        $data= $request->request->all();
        $user= new User();
        $taille=$data["taille"];
        $team=[];
        for ($i=1; $i <=$taille; $i++) { 
            array_push($team,$data["team$i"]);
        }
        $user->setPrenom($data['prenom']);
        $user->setNom($data['nom']);
        $user->setUsername($data['email']);
        $user->setPoste($data['poste']);
        $user->setPassword($encoder->encodePassword($user,"welcome"));
        $profil=$data['profil'];
        $user->setUserteam($team);
        $user->setRoles(["ROLE_$profil"]);
        $user->setTelephone($data['telephone']);
        $user->setStatut("actif");
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();
        return $this->json([
            'message'=>'Ajout Effectuer',
            'status'=>200
        ]);  
    }
    /**
     * @Route("/growteam")
     */
    public function growteam(StructureRepository $structureRepository,TeamPromoRepository $TeamPromo,SerializerInterface $serializer){
        $stucture=$structureRepository->findOneBy(['nom' => 'GROW']);
        $team=$TeamPromo->findBy(['structure' => $stucture->getId()]);
        $data = $serializer->serialize($team, 'json',[
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
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
    /**
     * @Route("/savestructure")
     */
    public function savestructure(Request $request,EntityManagerInterface $entityManagerInterface){
        $data=$request->request->all();
        $stucture=new Structure();
        $stucture->setNom($data['nom']);
        $entityManagerInterface->persist($stucture);
        $entityManagerInterface->flush();
        return $this->json([
            'message'=>'Ajout Effectuer',
            'status'=>200
        ]);  
    }
    /**
     * @Route("/listestructure")
     */
    public function liststructure(SerializerInterface $serializer,StructureRepository $stucture){
        $a=$stucture->findAll();
        $data = $serializer->serialize($a, 'json',[
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/addteamstructure")
     */
    public function addteamstructure(Request $request,EntityManagerInterface $entityManagerInterface,StructureRepository $structureRepository){
        $data=$request->request->all();
        $structure=$structureRepository->find($data['id']);
        $team=new TeamPromo();
        $team->setNom($data['nom']);
        $team->setStructure($structure);
        $entityManagerInterface->persist($team);
        $entityManagerInterface->flush();
        return $this->json([
            'message'=>'Ajout Effectuer',
            'status'=>200
        ]);  
    }
    /**
     * @Route("/oneteamstructure")
     */
        public function oneteamstructure(Request $request,SerializerInterface $serializer,TeamPromoRepository $teamPromoRepository){
            $data=$request->request->all();
            $team=$teamPromoRepository->teamdechaquestructure($data['id']);
            $data = $serializer->serialize($team, 'json',[
                'groups' => ['grow']
            ]);
            return new Response($data, 200, [
                'Content-Type' => 'application/json'
            ]);
        }
            /**
     * @Route("/saveoneteamstructure")
     */
    public function saveoneteamstructure(Request $request,StructureRepository $structureRepository,EntityManagerInterface $entityManagerInterface){
        $data=$request->request->all();
        $structure=$structureRepository->find($data['id']);
        $team= new TeamPromo();
        $team->setNom($data['nom']);
        $team->setStructure($structure);
        $entityManagerInterface->persist($team);
        $entityManagerInterface->flush();
        return $this->json([
            'message'=>'Ajout Effectuer',
            'status'=>200
        ]);  
    }
    
}
