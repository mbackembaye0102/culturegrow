<?php

namespace App\Controller;

use App\Entity\Allsession;
use App\Entity\Evaluation;
use App\Entity\Historiquesession;
use App\Entity\Structure;
use App\Entity\TeamPromo;
use App\Entity\User;
use App\Entity\UserTeamPromo;
use App\Form\EvaluationType;
use App\Repository\AllsessionRepository;
use App\Repository\EvaluationRepository;
use App\Repository\HistoriquesessionRepository;
use App\Repository\PosteRepository;
use App\Repository\StructureRepository;
use App\Repository\TeamPromoRepository;
use App\Repository\UserRepository;
use App\Repository\UserTeamPromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin", name="admin")
 */
class AdministrateurController extends AbstractController
{
    /**
     * @Route("/usergrow",name="utilisateurgrow",methods={"POST"})
     */
    public function usergrow(SerializerInterface $serializer,UserRepository $userRepository,StructureRepository $structureRepository){
        $structure=$structureRepository->findOneBy(['nom'=>'GROW']);
        $user=$userRepository->findBy(['structure'=>$structure->getId()]);
        $data = $serializer->serialize($user, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/growteam",name="growteam",methods={"POST"})
     */
    public function growteam(StructureRepository $structureRepository, TeamPromoRepository $TeamPromo, SerializerInterface $serializer)
    {
        $stucture = $structureRepository->findOneBy(['nom' => 'GROW']);
        $team = $TeamPromo->findBy(['structure' => $stucture->getId()]);
        $data = $serializer->serialize($team, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/growposte",name="growposte",methods={"POST"})
     */
    public function growposte(PosteRepository $posteTeam, SerializerInterface $serializer)
    {
        $team = $posteTeam->findAll();
        $data = $serializer->serialize($team, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
        /**
     * @Route("/saveusergrow",name="saveusergrow", methods={"POST"})
     */
    public function adduser(Request $request, UserPasswordEncoderInterface $encoder,StructureRepository $structureRepository, EntityManagerInterface $entityManagerInterface, TeamPromoRepository $teamPromoRepository,UserRepository $userRepository,AllsessionRepository $allsessionRepository)
    {
        $data = $request->request->all();
        
        $verifuser=$userRepository->findOneBy(['username'=>$data['email']]);
        if ($verifuser) {
            return $this->json([
                'message' => 'Email Déjas Utiliser',
                'status' => 201
            ]);
        }
        $verifuser1=$userRepository->findOneBy(['telephone'=>$data['telephone']]);
        if ($verifuser1) {
            return $this->json([
                'message' => 'Telephone Déjas Utiliser',
                'status' => 201
            ]);
        }
        $structure=$structureRepository->findOneBy(['nom'=>'GROW']);
        $user = new User();
        $taille = $data["taille"];
        $team = [];
        for ($i = 0; $i <= $taille; $i++) {
           // $a=$data["team$i"];
            $teams[$i] = $teamPromoRepository->findOneBy(['nom' => $data["team$i"]]);
          //  $teams[$i]->setUserteam();
            array_push($team, $teams[$i]);
        }
        $user->setPrenom($data['prenom']);
        $user->setNom($data['nom']);
        $user->setUsername($data['email']);
        $user->setPoste($data['poste']);
        $user->setPassword($encoder->encodePassword($user, "welcome"));
        $profil = $data['profil'];
        $user->setRoles(["ROLE_$profil"]);
        $user->setTelephone($data['telephone']);
        $user->setStatut("actif");
        $user->setImage("defaut.png");
        $user->setStructure($structure);
        if ($requestFile = $request->files->all()) {
            $file = $requestFile['image'];
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('chemin'), $fileName);
            $user->setImage($fileName);
        }
        $entityManagerInterface->persist($user);

        for ($i = 0; $i <=$taille; $i++) {
            $userTeamPromo = new UserTeamPromo();
            $userTeamPromo->setUser($user);
            $userTeamPromo->setTeamPromo($team[$i]);
            $entityManagerInterface->persist($userTeamPromo);
        }
        $session=$allsessionRepository->findBy(['structure'=>$structure->getId(),'statut'=>'active']);
        if ($session) {
            for ($i=0; $i <count($session) ; $i++) { 
                for ($j=0; $j < count($team); $j++) { 
                    $historique=new Historiquesession();
                    $historique->setTeam($team[$j]);
                    $historique->setUser($user);
                    $historique->setSession($session[$i]);
                    $entityManagerInterface->persist($historique);   
                }
            }
        }
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }
        /**
     * @Route("/detailuser")
     */
    public function detailuser(Request $request, UserRepository $userRepository,SerializerInterface $serializer)
    {
        $data = $request->request->all();
        $id = $data['id'];
        $user = $userRepository->find($id);
        $date=date('Y-m-d');
        
        if ($user) {
            $team = $this->getDoctrine()->getRepository(UserTeamPromo::class)->findBy(['user' => $user->getId()]);
            $a = [];
            for ($i = 0; $i < count($team); $i++) {
                array_push($a,$team[$i]->getTeamPromo()->getNom());
            }
            $tableau = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'prenom' => $user->getPrenom(),
                'nom' => $user->getNom(),
                'statut' => $user->getStatut(),
                'telephone' => $user->getTelephone(),
                'poste' => $user->getPoste(),
                'image' => $user->getImage(),
                'team' => $a,
            ];
            return $this->json($tableau);
        }
    }
        /**
     * @Route("/allstructure")
     */
    public function allstructure(StructureRepository $structureRepository, SerializerInterface $serializer)
    {
        $a = $structureRepository->findAll();
        $data = $serializer->serialize($a, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/oneteamstructure")
     */
    public function oneteamstructure(Request $request, SerializerInterface $serializer, TeamPromoRepository $teamPromoRepository)
    {
        $data = $request->request->all();
        $team = $teamPromoRepository->teamdechaquestructure($data['id']);
        $data = $serializer->serialize($team, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
        /**
     * @Route("/savesession")
     */
    public function savesession(Request $request,UserRepository $userRepository,TeamPromoRepository $teamPromoRepository,EntityManagerInterface $entityManagerInterface, StructureRepository $structureRepository,UserTeamPromoRepository $userTeamPromoRepository)
    {
        $data = $request->request->all();
        $taille = $data['taille'];
        $session = new Allsession();
        $structure = $structureRepository->find($data['structure']);
        if ($structure) {
            $tab = [];
             if ($taille !== null) {
                for ($i = 0; $i <= $data['taille']; $i++) {
                    array_push($tab, $data["choix$i"]);
                }
            }

            $session->setDate($data['date']);
            $session->setStatut("active");
        //    if ($data['all']=="good") {
        //        $rien=[];
        //         $allteams=$teamPromoRepository->findBy(['structure'=>$structure->getId()]);
        //         for ($i=0; $i < count($allteams); $i++) { 
        //             array_push($rien,$allteams[$i]->getNom());
        //         }
        //       //  $session->setLesteams($rien);

        //    }
           $session->setTeams($tab);
            $session->setConcerner($data['all']);
            $session->setStructure($structure);
            $entityManagerInterface->persist($session);
            // $historique=new Historiquesession();
            //  $historique->setSession($session);
            if ($data['all']=="good") {
                //EVALUATION PAR TEAMS ou EVALUATION PAS TEAMS ET PLUS
                // 1-Les user de cette structure
                $user=$userRepository->findBy(['structure'=>$data['structure']]);
                if ($user) {
                    for ($i=0; $i < count($user); $i++) { 
                        $userteam1=$userTeamPromoRepository->findBy(['user'=>$user[$i]->getId()]);
                        for ($j=0; $j < count($userteam1); $j++) { 
                            $historique=new Historiquesession();
                            $historique->setSession($session);
                            $historique->setUser($user[$i]);
                            $historique->setTeam($userteam1[$j]->getTeamPromo());
                            $entityManagerInterface->persist($historique);
                        }
                    }
                }
            }
            elseif($data['all']=="bad"){
                $a=$session->getTeams();
                for ($i=0; $i <count($a) ; $i++) { 
                    $team1=$teamPromoRepository->findOneBy(['nom'=>$a[$i]]);
                    $userteam2=$userTeamPromoRepository->findBy(['TeamPromo'=>$team1->getId()]);
                    for ($j=0; $j < count($userteam2); $j++) { 
                        $historique=new Historiquesession();
                        $historique->setSession($session);
                        $historique->setUser($userteam2->getUser());
                        $historique->setTeam($userteam2->getTeamPromo());
                        $entityManagerInterface->persist($historique);
                    }
                }
            }
            $entityManagerInterface->flush();
            return $this->json([
                'message' => 'Ajout Effectuer',
                'status' => 200
            ]);
        } else {
            return $this->json([
                'message' => $data['structure'],
                'status' => 200
            ]);
        }
    }
    /**
     * @Route("/addgrowteam")
     */
    public function addteamstructure(Request $request, EntityManagerInterface $entityManagerInterface, StructureRepository $structureRepository)
    {
        $data = $request->request->all();
        $structure = $structureRepository->findOneBy(['nom'=>'GROW']);
        $team = new TeamPromo();
        $team->setNom($data['nom']);
        if ($requestFile = $request->files->all()) {
            $file = $requestFile['image'];
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('chemin'), $fileName);
            $team->setImage($fileName);
        }
        $team->setStructure($structure);
        $entityManagerInterface->persist($team);
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }
        /**
     * @Route("/userteam")
     */
    public function userteam(Request $request, UserTeamPromoRepository $userTeamPromoRepository, SerializerInterface $serializer)
    {
        $data = $request->request->all();
        $user = $userTeamPromoRepository->findBy(['TeamPromo' => $data['id']]);

        $data = $serializer->serialize($user, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
        /**
     * @Route("/listestructure")
     */
    public function liststructure(SerializerInterface $serializer, StructureRepository $stucture)
    {
        $grow = $stucture->findOneBy(['nom' => 'GROW']);
        $a = $stucture->allstructure($grow->getId());
        $data = $serializer->serialize($a, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
        /**
     * @Route("/addstructure")
     */
    public function addstructure(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $data = $request->request->all();
        $stucture = new Structure();
        $stucture->setNom($data['nom']);
        $stucture->setImage("defaut.png");
        if ($requestFile = $request->files->all()) {
            $file = $requestFile['image'];
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('chemin'), $fileName);
            $stucture->setImage($fileName);
        }
        $entityManagerInterface->persist($stucture);
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }
        /**
     * @Route("/saveoneteamstructure")
     */
    public function saveoneteamstructure(Request $request, StructureRepository $structureRepository, EntityManagerInterface $entityManagerInterface)
    {
        $data = $request->request->all();
        $structure = $structureRepository->find($data['id']);
        $team = new TeamPromo();
        $team->setNom($data['nom']);
        $team->setStructure($structure);
        $team->setImage("defaut.png");
        if ($requestFile = $request->files->all()) {
            $file = $requestFile['image'];
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('chemin'), $fileName);
            $team->setImage($fileName);
        }
        $entityManagerInterface->persist($team);
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }
        /**
     * @Route("/saveuserteam")
     */
    public function saveuserteam(Request $request,UserRepository $userRepository, EntityManagerInterface $entityManagerInterface, UserPasswordEncoderInterface $encoder, TeamPromoRepository $teamPromoRepository)
    {
        $data = $request->request->all();
        $verifuser=$userRepository->findOneBy(['username'=>$data['email']]);
        if ($verifuser) {
            return $this->json([
                'message' => 'Email Déjas Utiliser',
                'status' => 201
            ]);
        }
        $verifuser1=$userRepository->findOneBy(['telephone'=>$data['telephone']]);
        if ($verifuser1) {
            return $this->json([
                'message' => 'Telephone Déjas Utiliser',
                'status' => 201
            ]);
        }
        $user = new User();
        $user->setPrenom($data['prenom']);
        $user->setNom($data['nom']);
        $user->setUsername($data['email']);
        $user->setPoste($data['poste']);
        $user->setPassword($encoder->encodePassword($user, "welcome"));
        $profil = "externe";
        $user->setRoles(["ROLE_$profil"]);
        $user->setTelephone($data['telephone']);
        $user->setStatut("actif");
        $user->setNomtuteur($data['nomtuteur']);
        $user->setTelephonetuteur($data['telephonetuteur']);
        $user->setImage("defaut.png");
        if ($requestFile = $request->files->all()) {
            $file = $requestFile['image'];
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('chemin'), $fileName);
            $user->setImage($fileName);
        }
        $mentor=$userRepository->findOneBy(['id'=>$data['mentor']]);
        $user->setMentor($mentor);
        $userTeamPromo = new UserTeamPromo();
        $userTeamPromo->setUser($user);
        $a = $teamPromoRepository->find($data['id']);
        $userTeamPromo->setTeamPromo($a);
        $user->setStructure($a->getStructure());
        $entityManagerInterface->persist($user);
        $entityManagerInterface->persist($userTeamPromo);
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }
    /**
     * @Route("/listementor")
     */
    public function listementor(UserRepository $userRepository,SerializerInterface $serializer){
        $user=$userRepository->testreq("ROLE_Mentor");
        $data = $serializer->serialize($user, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
        
    }
    /**
     * @Route("/teamisevaluer")
     */
    public function teamisevaluer(Request $request,UserTeamPromoRepository $userTeamPromoRepository,TeamPromoRepository $teamPromoRepository,SerializerInterface $serializer){
        $data=$request->request->all();
        if ($data['type']==="tateam") {
            $user=$userTeamPromoRepository->findBy(['user'=>$data['id']]);
           // dump(count($user));die();
            $team=[];
            for ($i=0; $i <count($user) ; $i++) { 
                $teams[$i]=$user[$i]->getTeamPromo();
                array_push($team,$teams[$i]);
            }
           // dump($team);die();
            $data = $serializer->serialize($team, 'json', [
                'groups' => ['grow']
            ]);
            return new Response($data, 200, [
                'Content-Type' => 'application/json'
            ]);
        }
        elseif($data['type']==="team"){
          //  dump($data['team']);die();
            $a=$data['team'];
            $team=[];
            for ($i=0; $i <count($a) ; $i++) { 
                $teams[$i]=$teamPromoRepository->findOneBy(['nom'=>$a[$i]]);
                array_push($team,$teams[$i]);
            }
            $data = $serializer->serialize($team, 'json', [
                'groups' => ['grow']
            ]);
            return new Response($data, 200, [
                'Content-Type' => 'application/json'
            ]);
        }
        elseif($data['type']==="tateam&team"){

        }
    }
            /**
     * @Route("/userteamevaluation")
     */
    public function userteamevaluation(Request $request,UserTeamPromoRepository $userTeamPromoRepository, TeamPromoRepository $teamPromoRepository, SerializerInterface $serializer)
    {
        $data = $request->request->all();
        $team = $teamPromoRepository->findOneBy(['nom' => $data['id']]);
        $user = $userTeamPromoRepository->findBy(['TeamPromo' => $team->getId()]);
        $data = $serializer->serialize($user, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/saveevaluation")
     */
    public function saveevaluation(Request $request,SerializerInterface $serializer,UserRepository $userRepository,EntityManagerInterface $entityManagerInterface){
        $data = $request->request->all();
      //  return $this->json(['team'=>$data['team']]);
        $evaluation= new Evaluation();
        $form=$this->createForm(EvaluationType::class,$evaluation);
        $form->submit($data);
        $evaluateur=$this->getUser();
        $evaluer=$userRepository->find($data['evaluer']);
        $evaluation->setEvaluateur($evaluateur);
        $evaluation->setEvaluer($evaluer);
        $evaluation->setTeam($data['team']);
        $entityManagerInterface->persist($evaluation);
        $entityManagerInterface->flush();
        $data = $serializer->serialize($evaluation, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);

    }
    /**
     * @Route("/detailsession")
     */
    public function detailsession(Request $request,AllsessionRepository $allsessionRepository,SerializerInterface $serializer){
        $data = $request->request->all();
        $session=$allsessionRepository->findBy(['structure'=>$data['id']]);
       // dump($session[0]);die();
        $data = $serializer->serialize($session, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
        
    }
    /**
     * @Route("/usersession")
     */
    public function usersession(Request $request,AllsessionRepository $allsessionRepository,SerializerInterface $serializer,UserRepository $userRepository){
        $data = $request->request->all();
        $user=$userRepository->find($data['id']);
        $session=$allsessionRepository->findBy(['structure'=>$user->getStructure()]);
        $team = $this->getDoctrine()->getRepository(UserTeamPromo::class)->findBy(['user' => $user->getId()]);
        $a = [];
        for ($i = 0; $i < count($team); $i++) {
            array_push($a,$team[$i]->getTeamPromo()->getNom());
        }
        //$bad=false;
        for ($i=0; $i <count($session) ; $i++) { 
            
            if ($session[$i]->getConcerner()=="bad") {
                $tab=[];
                $teambad=$session[$i]->getTeams();
                for ($i=0; $i < count($teambad); $i++) { 
                    if (in_array($teambad[$i],$a)) {
                        array_push($tab,$teambad[$i]);
                    }
                }
                if (count($tab)==0) {
                    unset($session[$i]);
                
                }
            }
        }
        sort($session);
        $data = $serializer->serialize($session, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/usersessionteam")
     */
    public function usersessionteam(Request $request,SerializerInterface $serializer,UserRepository $userRepository,AllsessionRepository $allsessionRepository){
        $data = $request->request->all();
        $user=$userRepository->find($data['iduser']);
        $session=$allsessionRepository->find($data['idsession']);
        $team = $this->getDoctrine()->getRepository(UserTeamPromo::class)->findBy(['user' => $user->getId()]);
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
            return $this->json(['team' => $userteam,]);
        
    }

    /**
     * @Route("/userdetailsessionevaluation")
     */
    public function userdetailsessionevaluation(Request $request,EvaluationRepository $evaluationRepository,SerializerInterface $serializer){
        $data = $request->request->all();
        $evaluation=$evaluationRepository->findBy([
            'team'=>$data['team'],
            'session'=>$data['idsession'],
            'evaluer'=>$data['iduser'],
        ]);
      //  return new JsonResponse($evaluation);
        $data1 = $serializer->serialize($evaluation, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data1, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/oneuser")
     */
    public function oneuser(Request $request,SerializerInterface $serializer,UserRepository $userRepository){
        $data = $request->request->all();
        $a = $userRepository->find( $data['id']);
        $data = $serializer->serialize($a, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/data")
     */
    public function userdata(Request $request,EvaluationRepository $evaluationRepository,AllsessionRepository $allsessionRepository){
        $data = $request->request->all();
        $perseverancelabel=[];
        $perseverancedata=[];
        $confiancelabel=[];
        $confiancedata=[];
        $collaborationlabel=[];
        $collaborationdata=[];
        $autonomielabel=[];
        $autonomiedata=[];
        $problemsolvinglabel=[];
        $problemsolvingdata=[];
        $transmissionlabel=[];
        $transmissiondata=[];
        $performancelabel=[];
        $performancedata=[];
        for ($i=0; $i <= $data['taille']; $i++) { 
            $perseverance=0;
            $confiance=0;
            $collaboration=0;
            $autonomie=0;
            $problemsolving=0;
            $transmission=0;
            $performance=0;
            $session=$allsessionRepository->findOneBy(['date'=>$data["date$i"]]);
            $evaluation=$evaluationRepository->findBy(['evaluer'=>$data['id'],'session'=>$session->getId()]);
            for ($j=0; $j < count($evaluation); $j++) { 
                $perseverance=$perseverance+$evaluation[$j]->getPerseverance();
                $confiance=$confiance+$evaluation[$j]->getConfiance();
                $collaboration=$collaboration+$evaluation[$j]->getCollaboration();
                $autonomie=$autonomie+$evaluation[$j]->getAutonomie();
                $problemsolving=$problemsolving+$evaluation[$j]->getProblemsolving();
                $transmission=$transmission+$evaluation[$j]->getTransmission();
                $performance=$performance+$evaluation[$j]->getPerformance();
            }
            $perseverance=$perseverance/count($evaluation);
            $confiance=$confiance/count($evaluation);
            $collaboration=$collaboration/count($evaluation);
            $autonomie=$autonomie/count($evaluation);
            $problemsolving=$problemsolving/count($evaluation);
            $transmission=$transmission/count($evaluation);
            $performance=$performance/count($evaluation);
            array_push($perseverancelabel,$data["date$i"]);
            array_push($perseverancedata,$perseverance);
            array_push($confiancelabel,$data["date$i"]);
            array_push($confiancedata,$confiance);
            array_push($collaborationlabel,$data["date$i"]);
            array_push($collaborationdata,$collaboration);
            array_push($autonomielabel,$data["date$i"]);
            array_push($autonomiedata,$autonomie);
            array_push($problemsolvinglabel,$data["date$i"]);
            array_push($problemsolvingdata,$problemsolving);
            array_push($transmissionlabel,$data["date$i"]);
            array_push($transmissiondata,$transmission);
            array_push($performancelabel,$data["date$i"]);
            array_push($performancedata,$performance);

        }
        return $this->json([
            'perseverancelabel'=>$perseverancelabel,
            'perseverancedata'=>$perseverancedata,
            'confiancelabel'=>$confiancelabel,
            'confiancedata'=>$confiancedata,
            'collaborationlabel'=>$collaborationlabel,
            'collaborationdata'=>$collaborationdata,
            'autonomielabel'=>$autonomielabel,
            'autonomiedata'=>$autonomiedata,
            'problemsolvinglabel'=>$problemsolvinglabel,
            'problemsolvingdata'=>$problemsolvingdata,
            'transmissionlabel'=>$transmissionlabel,
            'transmissiondata'=>$transmissiondata,
            'performancelabel'=>$performancelabel,
            'performancedata'=>$performancedata,
        ]);
    }
    /**
     * @Route("/lastevaluation")
     */
    public function lastevaluation(SerializerInterface $serializer,Request $request,EvaluationRepository $evaluationRepository,AllsessionRepository $allsessionRepository){
        $data=$request->request->all();
        $id=$data['id'];
        $anne=date('Y');
        $mois=["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Décembre"];
        $moyenneperseverance=[];
        $moyenneconfiance=[];
        $moyennecollaboration=[];
        $moyenneautonomie=[];
        $moyenneproblemsolving=[];
        $moyennetransmission=[];
        $moyenneperformance=[];
        $perseverance=0;
        $confiance=0;
        $collaboration=0;
        $autonomie=0;
        $problemsolving=0;
        $transmission=0;
        $performance=0;
        //On parcour cherche l'ensemble des all session qui ont ete crer dans l'annee
        //Pour chacune d'elle on cherche l'ensemble des evaluations don $id fut evaluer
        //On calcule la moyenne des notes de ce mois
        for ($i=0; $i <count($mois) ; $i++) { 
           $sessionevaluationdelannee=$allsessionRepository->findBy(['annee'=>$anne,'mois'=>$mois[$i]]);
           if (!$sessionevaluationdelannee) {
               //pas de session sur ce mois de l'anne
               array_push($moyenneperseverance,$perseverance);
               array_push($moyenneconfiance,$confiance);
               array_push($moyennecollaboration,$collaboration);
               array_push($moyenneautonomie,$autonomie);
               array_push($moyenneproblemsolving,$problemsolving);
               array_push($moyennetransmission,$transmission);
               array_push($moyenneperformance,$performance);
               $perseverance=0;
               $confiance=0;
               $collaboration=0;
               $autonomie=0;
               $problemsolving=0;
               $transmission=0;
               $performance=0;
           }
           else{
               dump($sessionevaluationdelannee);
               //il y'a eu session sur ce mois de l'annee
           // for ($j=0; $j <count($sessionevaluationdelannee) ; $j++) { 
               //pour chacune des sessions de ce mois il faut avoir les evalation
             //  if (!$sessionevaluationdelannee) {
                   
              // }
              // else{

               //}
           // }
           }

        }
        die();
        return $this->json([
            'date'=>$mois,
            'perseverance'=>$moyenneperseverance,
            'confiance'=>$moyenneconfiance,
            'collaboration'=>$moyennecollaboration,
            'autonomie'=>$moyenneautonomie,
            'problemsolving'=>$moyenneproblemsolving,
            'transmission'=>$moyennetransmission,
            'performance'=>$moyenneperformance
        ]);

    }
    /**
     * @Route("/alldate")
     */
    public function alldate(SerializerInterface $serializer,AllsessionRepository $allsessionRepository){
        $allevaluationuser=$allsessionRepository->findAll();
      
        $data = $serializer->serialize($allevaluationuser, 'json', [
            'groups' => ['note']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/persoevaluation")
     */
    public function persoevaluation(Request $request,EvaluationRepository $evaluationRepository,AllsessionRepository $allsessionRepository){
        $data=$request->request->all();
        $nombre=$data['nombre'];
        $date=[];
        $moyenneperseverance=[];
        $moyenneconfiance=[];
        $moyennecollaboration=[];
        $moyenneautonomie=[];
        $moyenneproblemsolving=[];
        $moyennetransmission=[];
        $moyenneperformance=[];
        $perseverance=0;
        $confiance=0;
        $collaboration=0;
        $autonomie=0;
        $problemsolving=0;
        $transmission=0;
        $performance=0;
        for ($i=0; $i <$nombre ; $i++) { 
            array_push($date,$data["date$i"]);
            $rt=$allsessionRepository->findOneBy(['date'=>$data["date$i"]]);
            $userevaluationdate=$evaluationRepository->findBy(['session'=>$rt->getId()]);
            for($j=0; $j<count($userevaluationdate);$j++){
                $perseverance=$perseverance+$userevaluationdate[$j]->getPerseverance();
                $confiance=$confiance+$userevaluationdate[$j]->getConfiance();
                $collaboration=$collaboration+$userevaluationdate[$j]->getCollaboration();
                $autonomie=$autonomie+$userevaluationdate[$j]->getAutonomie();
                $problemsolving=$problemsolving+$userevaluationdate[$j]->getProblemsolving();
                $transmission=$transmission+$userevaluationdate[$j]->getTransmission();
                $performance=$performance+$userevaluationdate[$j]->getPerformance();
            }
            if (count($userevaluationdate)==0) {
                $perseverance=0;
                $confiance=0;
                $collaboration=0;
                $autonomie=0;
                $problemsolving=0;
                $transmission=0;
                $performance=0;
            }
            else{
                $perseverance=$perseverance/count($userevaluationdate);
                $confiance=$confiance/count($userevaluationdate);
                $collaboration=$collaboration/count($userevaluationdate);
                $autonomie=$autonomie/count($userevaluationdate);
                $problemsolving=$problemsolving/count($userevaluationdate);
                $transmission=$transmission/count($userevaluationdate);
                $performance=$performance/count($userevaluationdate);
            }

            array_push($moyenneperseverance,$perseverance);
            array_push($moyenneconfiance,$confiance);
            array_push($moyennecollaboration,$collaboration);
            array_push($moyenneautonomie,$autonomie);
            array_push($moyenneproblemsolving,$problemsolving);
            array_push($moyennetransmission,$transmission);
            array_push($moyenneperformance,$performance);
            $perseverance=0;
            $confiance=0;
            $collaboration=0;
            $autonomie=0;
            $problemsolving=0;
            $transmission=0;
            $performance=0;
        }
        return $this->json([
            'date1'=>$date,
            'perseverance1'=>$moyenneperseverance,
            'confiance1'=>$moyenneconfiance,
            'collaboration1'=>$moyennecollaboration,
            'autonomie1'=>$moyenneautonomie,
            'problemsolving1'=>$moyenneproblemsolving,
            'transmission1'=>$moyennetransmission,
            'performance1'=>$moyenneperformance
        ]);
    }
}
