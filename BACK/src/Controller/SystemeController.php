<?php

namespace App\Controller;


use App\Entity\Structure;
use App\Entity\User;
use App\Repository\StructureRepository;
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
     * @Route("/addstructure",name="save")
     */
    public function saveuser(Request $request,EntityManagerInterface $entityManagerInterface){
        $data = json_decode($request->getContent(), true);
        $nom= $data['nom'];
        $structure= new Structure();
        $structure->setNom($nom);
        $entityManagerInterface->persist($structure);
        $entityManagerInterface->flush();
        return $this->json([
            'message'=>'Ajout Effectuer',
            'status'=>200
        ]);   
    }
    /**
     * @Route("/liststructure")
     */
    public function allstructure(Request $request,StructureRepository $structureRepository,SerializerInterface $serializer){
        $a=$structureRepository->findAll();
        $data = $serializer->serialize($a, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);

    }
    /**
     * @Route("/saveuser")
     */
    public function adduser(Request $request,EntityManagerInterface $entityManagerInterface,UserPasswordEncoderInterface $encoder){
        $data = json_decode($request->getContent(), true);
        $user= new User();
        $user->setPrenom($data['prenom']);
        $user->setNom($data['nom']);
        $user->setUsername($data['email']);
        $user->setPassword($encoder->encodePassword($user,"welcome"));
        $profil=$data['profil'];
        $user->setRoles(["ROLE_$profil"]);
        $user->setTelephone($data['telephone']);
        $user->setStatut($data['teams']);
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();
        return $this->json([
            'message'=>'Ajout Effectuer',
            'status'=>200
        ]);  
    }
    /**
     * @Route("/listuser")
     */
    public function listuser(Request $request,UserRepository $user,SerializerInterface $serializer){
        $a=$user->findAll();
        $data = $serializer->serialize($a, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
