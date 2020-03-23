<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Structure;
use App\Entity\TeamPromo;
use App\Entity\UserTeamPromo;
use App\Repository\UserRepository;
use App\Repository\PosteRepository;
use App\Repository\StructureRepository;
use App\Repository\TeamPromoRepository;
use App\Repository\UserTeamPromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
     * @Route("/admin", name="admin")
     */
class AdminController extends AbstractController
{
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
        $grow=$stucture->findOneBy(['nom'=>'GROW']);
        $a=$stucture->allstructure($grow->getId());
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
    /**
     * @Route("/userteam")
     */
    public function userteam(Request $request,UserTeamPromoRepository $userTeamPromoRepository,SerializerInterface $serializer){
        $data=$request->request->all();
        $user=$userTeamPromoRepository->findBy(['TeamPromo'=>$data['id']]);
        
        $data = $serializer->serialize($user, 'json',[
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/saveuserteam")
     */
    public function saveuserteam(Request $request,EntityManagerInterface $entityManagerInterface,UserPasswordEncoderInterface $encoder,TeamPromoRepository $teamPromoRepository){
        $data=$request->request->all();
        $user= new User();
        $user->setPrenom($data['prenom']);
        $user->setNom($data['nom']);
        $user->setUsername($data['email']);
        $user->setPoste($data['poste']);
        $user->setPassword($encoder->encodePassword($user,"welcome"));
        $profil="externe";
        $user->setRoles(["ROLE_$profil"]);
        $user->setTelephone($data['telephone']);
        $user->setStatut("actif");
        $entityManagerInterface->persist($user);
        $userTeamPromo= new UserTeamPromo();
        $userTeamPromo->setUser($user);
        $a=$teamPromoRepository->find($data['id']);
        $userTeamPromo->setTeamPromo($a);
        $entityManagerInterface->persist($userTeamPromo);
        $entityManagerInterface->flush();
        return $this->json([
            'message'=>'Ajout Effectuer',
            'status'=>200
        ]);  

    }
        /**
     * @Route("/structurepromo")
     */
    public function structurepromo(Request $request,TeamPromoRepository $teamPromoRepository,SerializerInterface $serializer){
        $data=$request->request->all();
        $a=$teamPromoRepository->findBy(['id'=>$data['id']]);
        $data = $serializer->serialize($a, 'json',[
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/yaya")
     */
    public function yaya(Request $request){
        $data=$request->request->all();
        dump($data);die();
    }
    
}
