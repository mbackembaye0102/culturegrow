<?php

namespace App\Controller;


use App\Entity\User;
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

class SystemeController extends AbstractController
{
    /**
     * @Route("/infos", name="systeme")
     */
    public function infos(){
        $a=$this->getUser();

        return new JsonResponse($a->getPrenom());
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
     * @Route("/saveuser")
     */
    public function adduser(Request $request){
        $data= $request->request->all();
        return new JsonResponse(['token' => $data['prenom']]);
    //    $id=2;
    //     $team=$teamPromoRepository->findOneBy(['id'=>$id]);
    //     $user= new User();
    //     $user->setPrenom($data['prenom']);
    //     $user->setNom($data['nom']);
    //     $user->setUsername($data['email']);
    //     $user->setPassword($encoder->encodePassword($user,"welcome"));
    //     $profil=$data['profil'];
    //     $user->setRoles(["ROLE_$profil"]);
    //     $user->setTelephone($data['telephone']);
    //     $user->setStatut("actif");
    //     $user->setTeampromo($team);
    //     $entityManagerInterface->persist($user);
    //     $entityManagerInterface->flush();
    //     return $this->json([
    //         'message'=>'Ajout Effectuer',
    //         'status'=>200
    //     ]);  
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
