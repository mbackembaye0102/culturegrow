<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Structure;
use App\Entity\TeamPromo;
use App\Entity\Allsession;
use App\Entity\Evaluation;
use App\Entity\UserTeamPromo;
use App\Repository\UserRepository;
use App\Repository\PosteRepository;
use App\Repository\StructureRepository;
use App\Repository\TeamPromoRepository;
use App\Repository\AllsessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserTeamPromoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/administrateur", name="admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/saveusergrow", methods={"POST"})
     */
    public function adduser(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManagerInterface, TeamPromoRepository $teamPromoRepository)
    {
        $data = $request->request->all();

        $user = new User();
        $taille = $data["taille"];
        $team = [];
        for ($i = 1; $i <= $taille; $i++) {
            $teams[$i] = $teamPromoRepository->findOneBy(['nom' => $data["team$i"]]);
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
        $user->setImage("https://i.ibb.co/kQB44c0/user.png");
        if ($requestFile = $request->files->all()) {
            $file = $requestFile['image'];
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('chemin'), $fileName);
            $user->setImage($fileName);
        }
        $entityManagerInterface->persist($user);

        for ($i = 0; $i < $taille; $i++) {
            $userTeamPromo = new UserTeamPromo();
            $userTeamPromo->setUser($user);
            $userTeamPromo->setTeamPromo($team[$i]);
            $entityManagerInterface->persist($userTeamPromo);
        }
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }
    /**
     * @Route("/growteam")
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
     * @Route("/growposte")
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
     * @Route("/listuser")
     */
    public function listuser(Request $request, UserRepository $user, SerializerInterface $serializer)
    {
        $a = $user->findAll();
        $data = $serializer->serialize($a, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/savestructure")
     */
    public function savestructure(Request $request, EntityManagerInterface $entityManagerInterface)
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
     * @Route("/addteamstructure")
     */
    public function addteamstructure(Request $request, EntityManagerInterface $entityManagerInterface, StructureRepository $structureRepository)
    {
        $data = $request->request->all();
        $structure = $structureRepository->find($data['id']);
        $team = new TeamPromo();
        $team->setNom($data['nom']);
        $team->setStructure($structure);
        $entityManagerInterface->persist($team);
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
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
     * @Route("/saveuserteam")
     */
    public function saveuserteam(Request $request, EntityManagerInterface $entityManagerInterface, UserPasswordEncoderInterface $encoder, TeamPromoRepository $teamPromoRepository)
    {
        $data = $request->request->all();
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
        $user->setImage("https://i.ibb.co/kQB44c0/user.png");
        if ($requestFile = $request->files->all()) {
            $file = $requestFile['image'];
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('chemin'), $fileName);
            $user->setImage($fileName);
        }
        $entityManagerInterface->persist($user);
        $userTeamPromo = new UserTeamPromo();
        $userTeamPromo->setUser($user);
        $a = $teamPromoRepository->find($data['id']);
        $userTeamPromo->setTeamPromo($a);
        $entityManagerInterface->persist($userTeamPromo);
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }
    /**
     * @Route("/structurepromo")
     */
    public function structurepromo(Request $request, TeamPromoRepository $teamPromoRepository, SerializerInterface $serializer)
    {
        $data = $request->request->all();
        $a = $teamPromoRepository->findBy(['id' => $data['id']]);
        $data = $serializer->serialize($a, 'json', [
            'groups' => ['grow']
        ]);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/detailuser")
     */
    public function detailuser(Request $request, UserRepository $userRepository,AllsessionRepository $allsessionRepository,SerializerInterface $serializer)
    {
        $data = $request->request->all();
        $id = $data['id'];
        $user = $userRepository->find($id);
        $date=date('Y-m-d');
        
        if ($user) {
            $team = $this->getDoctrine()->getRepository(UserTeamPromo::class)->findBy(['user' => $user->getId()]);
            $structure=$team[0]->getTeamPromo()->getStructure();
            $session=$allsessionRepository->findOneBy([
                'date'=>$date,
                'structure'=>$structure->getId()
            ]);
            $a = [];
            $isevaluer=array();
            $bordel=$session->getTeams();
            for ($i = 0; $i < count($team); $i++) {
                array_push($a,$team[$i]->getTeamPromo()->getNom());
                if (in_array($team[$i]->getTeamPromo()->getNom(),$bordel)) {
                    array_push($isevaluer,$team[$i]->getTeamPromo()->getNom());
                }
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
                'evaluation'=>$session->getStatut(),
                'all'=>$session->getConcerner(),
                'teamevaluer'=>$isevaluer
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
     * @Route("/savesession")
     */
    public function savesession(Request $request, EntityManagerInterface $entityManagerInterface, StructureRepository $structureRepository)
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
            $session->setTeams($tab);
            $session->setConcerner($data['all']);
            $session->setStructure($structure);
            $entityManagerInterface->persist($session);
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
     * @Route("/initialisationteamcrea")
     */
    public function initialisation(StructureRepository $structureRepository,UserRepository $userRepository,AllsessionRepository $allsessionRepository,EntityManagerInterface $entityManagerInterface){
        $alldate=["2020-05-02","2020-07-02","2020-12-02","2020-14-02","2020-19-02","2020-26-02","2020-28-02","2020-04-03","2020-06-03","2020-11-03","2020-13-03","2020-18-03","2020-20-03","2020-25-03","2020-01-04","2020-03-04","2020-08-04","2020-10-04","2020-15-04","2020-17-04","2020-22-04","2020-24-04","2020-29-04","2020-13-05","2020-15-05"];
        $structure = $structureRepository->findOneBy(['nom'=>'grow']);
        for ($i=0; $i <count($alldate) ; $i++) {
            $session = new Allsession();
            $session->setDate($alldate[$i]);
            $session->setStatut("active");
            $session->setTeams([]);
            $session->setConcerner("good");
            $session->setStructure($structure);
            $entityManagerInterface->persist($session);
            $entityManagerInterface->flush();
        }
        $allevalion=[
            ["team"=>"Team Créa","date"=>"2020-05-02","evaluateur"=>"abdoulaye","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"3","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"4","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Créa","date"=>"2020-05-02","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Créa","date"=>"2020-05-02","evaluateur"=>"abraham","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Créa","date"=>"2020-05-02","evaluateur"=>"abdoulaye","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Créa","date"=>"2020-05-02","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Créa","date"=>"2020-05-02","evaluateur"=>"abraham","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Créa","date"=>"2020-05-02","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Créa","date"=>"2020-05-02","evaluateur"=>"abraham","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"abdoulaye","evaluer"=>"faustin","perseverance"=>"6","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"glory","evaluer"=>"abdoulaye","perseverance"=>"0","confiance"=>"1","collaboration"=>"2","autonomie"=>"0","problemsolving"=>"1","transmission"=>"1","performance"=>"2"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"glory","evaluer"=>"abraham","perseverance"=>"0","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"fatou","evaluer"=>"abdoulaye","perseverance"=>"0","confiance"=>"2","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"2","transmission"=>"0","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"fatou","evaluer"=>"abraham","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"fatou","evaluer"=>"faustin","perseverance"=>"2","confiance"=>"0","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"0","transmission"=>"0","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"abraham","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"ngone","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"abraham","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"ngone","evaluer"=>"abraham","perseverance"=>"6","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"abraham","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"ngone","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"glory","evaluer"=>"faustin","perseverance"=>"0","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"abraham","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"yaya","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"6","performance"=>"8"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"yaya","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"6","collaboration"=>"7","autonomie"=>"10","problemsolving"=>"7","transmission"=>"5","performance"=>"8"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"yaya","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"5","collaboration"=>"7","autonomie"=>"5","problemsolving"=>"7","transmission"=>"5","performance"=>"7"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"anta","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"3","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"anta","evaluer"=>"abraham","perseverance"=>"6","confiance"=>"5","collaboration"=>"5","autonomie"=>"7","problemsolving"=>"6","transmission"=>"3","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"anta","evaluer"=>"faustin","perseverance"=>"6","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"6"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"mah","evaluer"=>"abraham","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"mah","evaluer"=>"abdoulaye","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"mah","evaluer"=>"faustin","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"mah","evaluer"=>"faustin","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"laye","evaluer"=>"abdoulaye","perseverance"=>"9","confiance"=>"9","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"9"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"laye","evaluer"=>"abraham","perseverance"=>"9","confiance"=>"9","collaboration"=>"9","autonomie"=>"9","problemsolving"=>"9","transmission"=>"8","performance"=>"9"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"laye","evaluer"=>"faustin","perseverance"=>"9","confiance"=>"8","collaboration"=>"9","autonomie"=>"9","problemsolving"=>"9","transmission"=>"9","performance"=>"9"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"mbacke","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"2","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"5","transmission"=>"2","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"mbacke","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"mbacke","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"aissata","evaluer"=>"abdoulaye","perseverance"=>"3","confiance"=>"2","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"aissata","evaluer"=>"abraham","perseverance"=>"3","confiance"=>"2","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"2","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-07-02","evaluateur"=>"aissata","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"2","collaboration"=>"4","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-12-02","evaluateur"=>"abraham","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-12-02","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-12-02","evaluateur"=>"abraham","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-12-02","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-12-02","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-12-02","evaluateur"=>"abraham","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-14-02","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-14-02","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-14-02","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-14-02","evaluateur"=>"abraham","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"3","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-14-02","evaluateur"=>"abraham","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-14-02","evaluateur"=>"abraham","evaluer"=>"abraham","perseverance"=>"6","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-19-02","evaluateur"=>"abdoulaye","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-19-02","evaluateur"=>"abdoulaye","evaluer"=>"abraham","perseverance"=>"6","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-19-02","evaluateur"=>"abdoulaye","evaluer"=>"faustin","perseverance"=>"6","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-19-02","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-19-02","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-19-02","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-26-02","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-26-02","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-26-02","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"khady","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"5","transmission"=>"3","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"khady","evaluer"=>"abraham","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"khady","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"mah","evaluer"=>"abdoulaye","perseverance"=>"2","confiance"=>"3","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"mah","evaluer"=>"abraham","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"laye","evaluer"=>"abdoulaye","perseverance"=>"8","confiance"=>"8","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"laye","evaluer"=>"abraham","perseverance"=>"9","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"8","transmission"=>"7","performance"=>"8"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"laye","evaluer"=>"faustin","perseverance"=>"10","confiance"=>"8","collaboration"=>"8","autonomie"=>"9","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"mbacke","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"mah","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"mbacke","evaluer"=>"abraham","perseverance"=>"1","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"mbacke","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"yaya","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"yaya","evaluer"=>"abraham","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"yaya","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"aissata","evaluer"=>"abdoulaye","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"aissata","evaluer"=>"abraham","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"aissata","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"fatou","evaluer"=>"abdoulaye","perseverance"=>"0","confiance"=>"2","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"2","transmission"=>"0","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"fatou","evaluer"=>"abraham","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"fatou","evaluer"=>"faustin","perseverance"=>"1","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"2","performance"=>"2"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"abraham","perseverance"=>"7","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
        ["team"=>"Team Créa","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"faustin","perseverance"=>"7","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-04-03","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-04-03","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-04-03","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
        ["team"=>"Team Créa","date"=>"2020-06-03","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-06-03","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-06-03","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"1","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-06-03","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"1","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-11-03","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
        ["team"=>"Team Créa","date"=>"2020-11-03","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"4","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-11-03","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"1","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"1","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-18-03","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-18-03","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-18-03","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"1","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-20-03","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"6","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-20-03","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
        ["team"=>"Team Créa","date"=>"2020-20-03","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"1","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-25-03","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-25-03","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-25-03","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"1","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"Team Créa","date"=>"2020-03-04","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-03-04","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-03-04","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-03-04","evaluateur"=>"mbacke","evaluer"=>"abdoulaye","perseverance"=>"3","confiance"=>"3","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-03-04","evaluateur"=>"mbacke","evaluer"=>"abraham","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-03-04","evaluateur"=>"mbacke","evaluer"=>"faustin","perseverance"=>"2","confiance"=>"3","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-08-04","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-08-04","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-08-04","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-10-04","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-10-04","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-10-04","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-15-04","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-15-04","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-15-04","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-17-04","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-17-04","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-17-04","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-22-04","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-22-04","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-22-04","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-24-04","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-24-04","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-24-04","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-29-04","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-29-04","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-29-04","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-13-05","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"Team Créa","date"=>"2020-13-05","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-13-05","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Créa","date"=>"2020-15-05","evaluateur"=>"faustin","evaluer"=>"abdoulaye","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
        ["team"=>"Team Créa","date"=>"2020-15-05","evaluateur"=>"faustin","evaluer"=>"abraham","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Créa","date"=>"2020-15-05","evaluateur"=>"faustin","evaluer"=>"faustin","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"]
        ];
        
        for ($i=0; $i <count($allevalion) ; $i++) { 
            $evaluation= new Evaluation();
            $a=$allevalion[$i]['evaluateur'];
            $b=$allevalion[$i]['evaluer'];
            $evaluateur=$userRepository->findOneBy(['username'=>$a]);
            $evaluer=$userRepository->findOneBy(['username'=>$b]);
            $evaluation->setEvaluateur($evaluateur);
            $evaluation->setEvaluer($evaluer);
            $evaluation->setPerseverance($allevalion[$i]['perseverance']);
            $evaluation->setConfiance($allevalion[$i]['confiance']);
            $evaluation->setCollaboration($allevalion[$i]['collaboration']);
            $evaluation->setAutonomie($allevalion[$i]['autonomie']);
            $evaluation->setProblemsolving($allevalion[$i]['problemsolving']);
            $evaluation->setTransmission($allevalion[$i]['transmission']);
            $evaluation->setPerformance($allevalion[$i]['performance']);
            $evaluation->setTeam($allevalion[$i]['team']);
            $rien=$allevalion[$i]['date'];
            $alpha=$allsessionRepository->findOneBy(['date'=>$rien]);
            $evaluation->setSession($alpha);
            $entityManagerInterface->persist($evaluation);
        }
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }
        /**
     * @Route("/initialisationtgrowacademy")
     */
    public function initialisationgrowacademy(StructureRepository $structureRepository,UserRepository $userRepository,AllsessionRepository $allsessionRepository,EntityManagerInterface $entityManagerInterface){

        $allevalion=
        [
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"2","confiance"=>"2","collaboration"=>"4","autonomie"=>"1","problemsolving"=>"1","transmission"=>"4","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"fatou","evaluer"=>"anta","perseverance"=>"0","confiance"=>"1","collaboration"=>"1","autonomie"=>"2","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"yacine","evaluer"=>"anta","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"khady","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"4"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"7","transmission"=>"7","performance"=>"6"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"2","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"2","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"3","transmission"=>"5","performance"=>"5"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"fatou","evaluer"=>"aissata","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"khady","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"7","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"7","transmission"=>"7","performance"=>"6"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"yacine","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"anta","evaluer"=>"aissata","perseverance"=>"6","confiance"=>"7","collaboration"=>"5","autonomie"=>"7","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"3","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"aissata","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"3","performance"=>"3"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"khady","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"fatou","evaluer"=>"fatou","perseverance"=>"0","confiance"=>"0","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"0","transmission"=>"1","performance"=>"1"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"yacine","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"anta","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"5"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"3","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"fatou","evaluer"=>"glory","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"1","transmission"=>"2","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"yacine","evaluer"=>"glory","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"khady","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"3","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"7","confiance"=>"7","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"7","transmission"=>"5","performance"=>"6"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"aissata","evaluer"=>"khady","perseverance"=>"3","confiance"=>"1","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"fatou","evaluer"=>"khady","perseverance"=>"1","confiance"=>"0","collaboration"=>"0","autonomie"=>"1","problemsolving"=>"1","transmission"=>"0","performance"=>"0"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"yacine","evaluer"=>"khady","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"khady","evaluer"=>"khady","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"5"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"anta","evaluer"=>"khady","perseverance"=>"5","confiance"=>"6","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"2","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"aissata","evaluer"=>"ngone","perseverance"=>"2","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"fatou","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"1","collaboration"=>"0","autonomie"=>"1","problemsolving"=>"0","transmission"=>"1","performance"=>"1"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"yacine","evaluer"=>"ngone","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"khady","evaluer"=>"ngone","perseverance"=>"3","confiance"=>"2","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"3"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"anta","evaluer"=>"ngone","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"1","confiance"=>"2","collaboration"=>"2","autonomie"=>"0","problemsolving"=>"1","transmission"=>"0","performance"=>"1"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"aissata","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"1","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"fatou","evaluer"=>"yacine","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"1","problemsolving"=>"1","transmission"=>"0","performance"=>"0"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"khady","evaluer"=>"yacine","perseverance"=>"2","confiance"=>"4","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"yacine","evaluer"=>"yacine","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"anta","evaluer"=>"yacine","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"grow academy","date"=>"2020-05-02","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"2","confiance"=>"2","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"2"],
            ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"khady","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"6","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"khady","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"2","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"2","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"khady","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"khady","evaluer"=>"khady","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"khady","evaluer"=>"ngone","perseverance"=>"4","confiance"=>"2","collaboration"=>"2","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"khady","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"khady","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"ngone","evaluer"=>"aissata","perseverance"=>"7","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"ngone","evaluer"=>"yacine","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"ngone","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"ngone","evaluer"=>"khady","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"ngone","evaluer"=>"ngone","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"ngone","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"ngone","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"fatou","evaluer"=>"aissata","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"2","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"fatou","evaluer"=>"yacine","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"fatou","evaluer"=>"khady","perseverance"=>"0","confiance"=>"0","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"fatou","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"1","autonomie"=>"0","problemsolving"=>"1","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"fatou","evaluer"=>"glory","perseverance"=>"1","confiance"=>"1","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"fatou","evaluer"=>"anta","perseverance"=>"1","confiance"=>"1","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"1","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"fatou","evaluer"=>"fatou","perseverance"=>"0","confiance"=>"1","collaboration"=>"1","autonomie"=>"0","problemsolving"=>"1","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"anta","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"anta","evaluer"=>"yacine","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"anta","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"anta","evaluer"=>"khady","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"anta","evaluer"=>"ngone","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"3","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"6","confiance"=>"7","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"7","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"7","problemsolving"=>"5","transmission"=>"7","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"2","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"aissata","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"2","transmission"=>"3","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"aissata","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"1","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"aissata","evaluer"=>"khady","perseverance"=>"3","confiance"=>"1","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"aissata","evaluer"=>"ngone","perseverance"=>"3","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"2","confiance"=>"2","collaboration"=>"4","autonomie"=>"2","problemsolving"=>"3","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"3","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"1","confiance"=>"2","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"1","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"2","confiance"=>"3","collaboration"=>"4","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"1","confiance"=>"2","collaboration"=>"1","autonomie"=>"0","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-07-02","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"2","confiance"=>"4","collaboration"=>"5","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"fatou","evaluer"=>"aissata","perseverance"=>"1","confiance"=>"2","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"2","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"fatou","evaluer"=>"yacine","perseverance"=>"0","confiance"=>"0","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"aissata","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"2","transmission"=>"3","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"fatou","evaluer"=>"fatou","perseverance"=>"0","confiance"=>"1","collaboration"=>"1","autonomie"=>"0","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"fatou","evaluer"=>"khady","perseverance"=>"0","confiance"=>"0","collaboration"=>"1","autonomie"=>"0","problemsolving"=>"1","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"fatou","evaluer"=>"ngone","perseverance"=>"1","confiance"=>"0","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"0","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"fatou","evaluer"=>"glory","perseverance"=>"1","confiance"=>"3","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"aissata","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"aissata","evaluer"=>"khady","perseverance"=>"3","confiance"=>"2","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"3","transmission"=>"1","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"fatou","evaluer"=>"anta","perseverance"=>"0","confiance"=>"2","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"2","transmission"=>"3","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"aissata","evaluer"=>"ngone","perseverance"=>"3","confiance"=>"1","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"1","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"khady","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"khady","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"3","collaboration"=>"2","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"khady","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"khady","evaluer"=>"khady","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"khady","evaluer"=>"ngone","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"khady","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-12-02","evaluateur"=>"khady","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"fatou","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"khady","perseverance"=>"2","confiance"=>"1","collaboration"=>"1","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"ngone","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"3","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"5","collaboration"=>"6","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"1","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"khady","perseverance"=>"4","confiance"=>"1","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"ngone","perseverance"=>"3","confiance"=>"1","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"2","confiance"=>"3","collaboration"=>"4","autonomie"=>"2","problemsolving"=>"3","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"3","confiance"=>"5","collaboration"=>"5","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"2","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"3","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"4","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"khady","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"ngone","perseverance"=>"3","confiance"=>"2","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"glory","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"4","transmission"=>"5","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"khady","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"khady","evaluer"=>"yacine","perseverance"=>"2","confiance"=>"3","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"khady","evaluer"=>"fatou","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"2","transmission"=>"3","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"khady","evaluer"=>"khady","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"khady","evaluer"=>"ngone","perseverance"=>"2","confiance"=>"2","collaboration"=>"1","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"khady","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"khady","evaluer"=>"anta","perseverance"=>"3","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"yacine","evaluer"=>"yacine","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"yacine","evaluer"=>"aissata","perseverance"=>"3","confiance"=>"3","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"yacine","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"fatou","evaluer"=>"aissata","perseverance"=>"1","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"2","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"yacine","evaluer"=>"khady","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"2","transmission"=>"2","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"fatou","evaluer"=>"yacine","perseverance"=>"1","confiance"=>"0","collaboration"=>"0","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"fatou","evaluer"=>"fatou","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"fatou","evaluer"=>"khady","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"yacine","evaluer"=>"ngone","perseverance"=>"2","confiance"=>"3","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"fatou","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"yacine","evaluer"=>"glory","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"fatou","evaluer"=>"glory","perseverance"=>"1","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"yacine","evaluer"=>"anta","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"aissata","evaluer"=>"yacine","perseverance"=>"2","confiance"=>"2","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"aissata","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"aissata","evaluer"=>"khady","perseverance"=>"3","confiance"=>"1","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"aissata","evaluer"=>"ngone","perseverance"=>"3","confiance"=>"1","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"fatou","evaluer"=>"anta","perseverance"=>"1","confiance"=>"4","collaboration"=>"4","autonomie"=>"2","problemsolving"=>"2","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"3","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"1","confiance"=>"1","collaboration"=>"1","autonomie"=>"0","problemsolving"=>"2","transmission"=>"1","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"1","confiance"=>"3","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"1","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"1","confiance"=>"3","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"0","confiance"=>"4","collaboration"=>"6","autonomie"=>"4","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-19-02","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"3","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"aissata","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"aissata","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"4","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"2","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"aissata","evaluer"=>"khady","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"aissata","evaluer"=>"ngone","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"3","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"2","confiance"=>"3","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"1","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"3","confiance"=>"3","collaboration"=>"4","autonomie"=>"2","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"fatou","evaluer"=>"aissata","perseverance"=>"0","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"4","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"fatou","evaluer"=>"yacine","perseverance"=>"0","confiance"=>"1","collaboration"=>"1","autonomie"=>"0","problemsolving"=>"1","transmission"=>"2","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"fatou","evaluer"=>"fatou","perseverance"=>"0","confiance"=>"2","collaboration"=>"2","autonomie"=>"0","problemsolving"=>"1","transmission"=>"3","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"fatou","evaluer"=>"glory","perseverance"=>"1","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-26-02","evaluateur"=>"fatou","evaluer"=>"anta","perseverance"=>"0","confiance"=>"3","collaboration"=>"4","autonomie"=>"0","problemsolving"=>"2","transmission"=>"5","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"khady","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"khady","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"khady","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"5","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"khady","evaluer"=>"khady","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"khady","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"khady","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"khady","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"aissata","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"aissata","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"aissata","evaluer"=>"khady","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"2","transmission"=>"2","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"aissata","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"6","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"fatou","evaluer"=>"aissata","perseverance"=>"1","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"fatou","evaluer"=>"yacine","perseverance"=>"1","confiance"=>"1","collaboration"=>"2","autonomie"=>"0","problemsolving"=>"1","transmission"=>"3","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"fatou","evaluer"=>"fatou","perseverance"=>"1","confiance"=>"0","collaboration"=>"1","autonomie"=>"0","problemsolving"=>"0","transmission"=>"1","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"fatou","evaluer"=>"khady","perseverance"=>"0","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"fatou","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"fatou","evaluer"=>"glory","perseverance"=>"0","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"fatou","evaluer"=>"anta","perseverance"=>"1","confiance"=>"2","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"1","transmission"=>"4","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"aissata","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"yacine","perseverance"=>"6","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"fatou","perseverance"=>"7","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"khady","perseverance"=>"5","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"ngone","perseverance"=>"5","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"7","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"3","confiance"=>"4","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"khady","evaluer"=>"aissata","perseverance"=>"3","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"6","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"khady","evaluer"=>"yacine","perseverance"=>"2","confiance"=>"2","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"khady","evaluer"=>"khady","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"khady","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"6","transmission"=>"4","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"khady","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"2","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"6","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"aissata","evaluer"=>"yacine","perseverance"=>"4","confiance"=>"3","collaboration"=>"2","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"aissata","evaluer"=>"fatou","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"aissata","evaluer"=>"khady","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"aissata","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"6","autonomie"=>"7","problemsolving"=>"5","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"2","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"khady","perseverance"=>"4","confiance"=>"2","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"4","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"5","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"khady","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"khady","evaluer"=>"yacine","perseverance"=>"2","confiance"=>"2","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"khady","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"khady","evaluer"=>"khady","perseverance"=>"3","confiance"=>"3","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"khady","evaluer"=>"glory","perseverance"=>"4","confiance"=>"6","collaboration"=>"4","autonomie"=>"6","problemsolving"=>"6","transmission"=>"4","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"khady","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"fatou","evaluer"=>"aissata","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"3","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"fatou","evaluer"=>"yacine","perseverance"=>"1","confiance"=>"1","collaboration"=>"1","autonomie"=>"0","problemsolving"=>"1","transmission"=>"3","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"fatou","evaluer"=>"fatou","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"fatou","evaluer"=>"khady","perseverance"=>"0","confiance"=>"0","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"2","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"fatou","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"fatou","evaluer"=>"glory","perseverance"=>"4","confiance"=>"3","collaboration"=>"2","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-11-03","evaluateur"=>"fatou","evaluer"=>"anta","perseverance"=>"2","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"5","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"yacine","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"fatou","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"khady","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"glory","perseverance"=>"6","confiance"=>"5","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"faustin","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"7","collaboration"=>"7","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"yacine","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"7"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"fatou","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"khady","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"khady","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"ngone","perseverance"=>"1","confiance"=>"0","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"yacine","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"fatou","perseverance"=>"6","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"khady","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"fatou","evaluer"=>"aissata","perseverance"=>"2","confiance"=>"4","collaboration"=>"5","autonomie"=>"3","problemsolving"=>"5","transmission"=>"5","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"yacine","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"fatou","evaluer"=>"yacine","perseverance"=>"5","confiance"=>"1","collaboration"=>"0","autonomie"=>"1","problemsolving"=>"2","transmission"=>"3","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"fatou","evaluer"=>"fatou","perseverance"=>"0","confiance"=>"2","collaboration"=>"2","autonomie"=>"0","problemsolving"=>"0","transmission"=>"2","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"aissata","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"aissata","evaluer"=>"khady","perseverance"=>"4","confiance"=>"2","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"aissata","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"5","performance"=>"7"],
        ["team"=>"grow academy","date"=>"2020-13-03","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"6","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"khady","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"khady","evaluer"=>"fatou","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"khady","evaluer"=>"khady","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"khady","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"4","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"khady","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"2","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"3","confiance"=>"2","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"1","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"3","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"4","confiance"=>"4","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"3","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"4","confiance"=>"3","collaboration"=>"5","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"yacine","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"fatou","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-25-03","evaluateur"=>"faustin","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"khady","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"ngone","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"grow academy","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"3","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-01-04","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"5","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-01-04","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"7","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-01-04","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"7","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-01-04","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"5","confiance"=>"3","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"2","transmission"=>"1","performance"=>"2"],
        ["team"=>"grow academy","date"=>"2020-03-04","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-03-04","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-03-04","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"3","confiance"=>"3","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"2","transmission"=>"1","performance"=>"1"],
        ["team"=>"grow academy","date"=>"2020-17-04","evaluateur"=>"faustin","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
    ["team"=>"grow academy","date"=>"2020-17-04","evaluateur"=>"faustin","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
    ["team"=>"grow academy","date"=>"2020-17-04","evaluateur"=>"faustin","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
    ["team"=>"grow academy","date"=>"2020-17-04","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"4","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"6"],
    ["team"=>"grow academy","date"=>"2020-17-04","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
    ["team"=>"grow academy","date"=>"2020-17-04","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"2","performance"=>"3"],
    ["team"=>"grow academy","date"=>"2020-17-04","evaluateur"=>"mbacke","evaluer"=>"aissata","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
    ["team"=>"grow academy","date"=>"2020-17-04","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
    ["team"=>"grow academy","date"=>"2020-22-04","evaluateur"=>"anta","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-22-04","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"6","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-22-04","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-24-04","evaluateur"=>"anta","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-24-04","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-24-04","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-29-04","evaluateur"=>"anta","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-29-04","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-29-04","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-05","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-05","evaluateur"=>"glory","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"6","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"7","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-05","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-13-05","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-13-05","evaluateur"=>"anta","evaluer"=>"aissata","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-13-05","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"6","confiance"=>"6","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-13-05","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"7","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-13-05","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"7","performance"=>"6"],
        ["team"=>"grow academy","date"=>"2020-15-05","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"5","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-15-05","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"6","confiance"=>"6","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-15-05","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"5","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-15-05","evaluateur"=>"aissata","evaluer"=>"aissata","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
        ["team"=>"grow academy","date"=>"2020-15-05","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-15-05","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"grow academy","date"=>"2020-15-05","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"5","performance"=>"3"],
        ["team"=>"grow academy","date"=>"2020-15-05","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ];
        for ($i=0; $i <count($allevalion) ; $i++) { 
            $evaluation= new Evaluation();
            $a=$allevalion[$i]['evaluateur'];
            $b=$allevalion[$i]['evaluer'];
            $evaluateur=$userRepository->findOneBy(['username'=>$a]);
            $evaluer=$userRepository->findOneBy(['username'=>$b]);
            $evaluation->setEvaluateur($evaluateur);
            $evaluation->setEvaluer($evaluer);
            $evaluation->setPerseverance($allevalion[$i]['perseverance']);
            $evaluation->setConfiance($allevalion[$i]['confiance']);
            $evaluation->setCollaboration($allevalion[$i]['collaboration']);
            $evaluation->setAutonomie($allevalion[$i]['autonomie']);
            $evaluation->setProblemsolving($allevalion[$i]['problemsolving']);
            $evaluation->setTransmission($allevalion[$i]['transmission']);
            $evaluation->setPerformance($allevalion[$i]['performance']);
            $evaluation->setTeam($allevalion[$i]['team']);
            $rien=$allevalion[$i]['date'];
            $alpha=$allsessionRepository->findOneBy(['date'=>$rien]);
            $evaluation->setSession($alpha);
            $entityManagerInterface->persist($evaluation);
        }
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }
            /**
     * @Route("/initialisationtteambusness")
     */
    public function initialisationteambusness(StructureRepository $structureRepository,UserRepository $userRepository,AllsessionRepository $allsessionRepository,EntityManagerInterface $entityManagerInterface){
       $allevalion=[
        ["team"=>"Team Business","date"=>"2020-07-02","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
        ["team"=>"Team Business","date"=>"2020-12-02","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"Team Business","date"=>"2020-14-02","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
        ["team"=>"Team Business","date"=>"2020-19-02","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"Team Business","date"=>"2020-21-02","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"Team Business","date"=>"2020-21-02","evaluateur"=>"laye","evaluer"=>"mah","perseverance"=>"8","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
        ["team"=>"Team Business","date"=>"2020-21-02","evaluateur"=>"mbacke","evaluer"=>"mah","perseverance"=>"3","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Business","date"=>"2020-21-02","evaluateur"=>"abdoulaye","evaluer"=>"mah","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Business","date"=>"2020-21-02","evaluateur"=>"fatou","evaluer"=>"mah","perseverance"=>"2","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"4","performance"=>"1"],
        ["team"=>"Team Business","date"=>"2020-21-02","evaluateur"=>"aissata","evaluer"=>"mah","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Business","date"=>"2020-23-02","evaluateur"=>"anta","evaluer"=>"mah","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
        ["team"=>"Team Business","date"=>"2020-23-02","evaluateur"=>"yaya","evaluer"=>"mah","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Business","date"=>"2020-26-02","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"1","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Business","date"=>"2020-28-02","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
        ["team"=>"Team Business","date"=>"2020-04-03","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
        ["team"=>"Team Business","date"=>"2020-18-03","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Business","date"=>"2020-20-03","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
        ["team"=>"Team Business","date"=>"2020-20-03","evaluateur"=>"faustin","evaluer"=>"mah","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
        ["team"=>"Team Business","date"=>"2020-20-03","evaluateur"=>"yaya","evaluer"=>"mah","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
        ["team"=>"Team Business","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"mah","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
        ["team"=>"Team Business","date"=>"2020-20-03","evaluateur"=>"mbacke","evaluer"=>"mah","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Business","date"=>"2020-20-03","evaluateur"=>"laye","evaluer"=>"mah","perseverance"=>"8","confiance"=>"9","collaboration"=>"9","autonomie"=>"9","problemsolving"=>"9","transmission"=>"9","performance"=>"9"],
        ["team"=>"Team Business","date"=>"2020-25-03","evaluateur"=>"mbacke","evaluer"=>"mah","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Business","date"=>"2020-10-04","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"2","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
        ["team"=>"Team Business","date"=>"2020-24-04","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
        ["team"=>"Team Business","date"=>"2020-24-04","evaluateur"=>"faustin","evaluer"=>"mah","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
        ["team"=>"Team Business","date"=>"2020-29-04","evaluateur"=>"mah","evaluer"=>"mah","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
       ];
        for ($i=0; $i <count($allevalion) ; $i++) { 
            $evaluation= new Evaluation();
            $a=$allevalion[$i]['evaluateur'];
            $b=$allevalion[$i]['evaluer'];
            $evaluateur=$userRepository->findOneBy(['username'=>$a]);
            $evaluer=$userRepository->findOneBy(['username'=>$b]);
            $evaluation->setEvaluateur($evaluateur);
            $evaluation->setEvaluer($evaluer);
            $evaluation->setPerseverance($allevalion[$i]['perseverance']);
            $evaluation->setConfiance($allevalion[$i]['confiance']);
            $evaluation->setCollaboration($allevalion[$i]['collaboration']);
            $evaluation->setAutonomie($allevalion[$i]['autonomie']);
            $evaluation->setProblemsolving($allevalion[$i]['problemsolving']);
            $evaluation->setTransmission($allevalion[$i]['transmission']);
            $evaluation->setPerformance($allevalion[$i]['performance']);
            $evaluation->setTeam($allevalion[$i]['team']);
            $rien=$allevalion[$i]['date'];
            $alpha=$allsessionRepository->findOneBy(['date'=>$rien]);
            $evaluation->setSession($alpha);
            $entityManagerInterface->persist($evaluation);
        }
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }

    /**
     * @Route("/initialisationtteamtech")
     */
    public function initialisationteamtech(StructureRepository $structureRepository,UserRepository $userRepository,AllsessionRepository $allsessionRepository,EntityManagerInterface $entityManagerInterface){
        $allevalion=[
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"8","confiance"=>"3","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"7","transmission"=>"3","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"rodrigue","evaluer"=>"laye","perseverance"=>"8","confiance"=>"4","collaboration"=>"5","autonomie"=>"3","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"laye","evaluer"=>"laye","perseverance"=>"9","confiance"=>"9","collaboration"=>"9","autonomie"=>"9","problemsolving"=>"9","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"0","confiance"=>"7","collaboration"=>"5","autonomie"=>"3","problemsolving"=>"6","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"1","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"rodrigue","evaluer"=>"anta","perseverance"=>"3","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"laye","evaluer"=>"anta","perseverance"=>"8","confiance"=>"8","collaboration"=>"8","autonomie"=>"9","problemsolving"=>"9","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"rodrigue","evaluer"=>"mbacke","perseverance"=>"10","confiance"=>"6","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"7","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"8","confiance"=>"7","collaboration"=>"8","autonomie"=>"7","problemsolving"=>"7","transmission"=>"5","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"laye","evaluer"=>"mbacke","perseverance"=>"9","confiance"=>"9","collaboration"=>"8","autonomie"=>"9","problemsolving"=>"9","transmission"=>"8","performance"=>"9"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"6","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"8","transmission"=>"7","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"rodrigue","evaluer"=>"yaya","perseverance"=>"8","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"3","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"2","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"8","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"rodrigue","evaluer"=>"glory","perseverance"=>"0","confiance"=>"4","collaboration"=>"5","autonomie"=>"3","problemsolving"=>"3","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"1","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"7","collaboration"=>"8","autonomie"=>"7","problemsolving"=>"8","transmission"=>"6","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"laye","evaluer"=>"glory","perseverance"=>"8","confiance"=>"9","collaboration"=>"9","autonomie"=>"9","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"yaya","evaluer"=>"rodrigue","perseverance"=>"8","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"2","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"mbacke","evaluer"=>"rodrigue","perseverance"=>"3","confiance"=>"2","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"2","transmission"=>"1","performance"=>"1"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"rodrigue","evaluer"=>"rodrigue","perseverance"=>"10","confiance"=>"6","collaboration"=>"5","autonomie"=>"8","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-05-02","evaluateur"=>"laye","evaluer"=>"rodrigue","perseverance"=>"9","confiance"=>"7","collaboration"=>"9","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"0","confiance"=>"7","collaboration"=>"5","autonomie"=>"8","problemsolving"=>"8","transmission"=>"5","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"8","confiance"=>"6","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"8","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"8","problemsolving"=>"8","transmission"=>"7","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"7","transmission"=>"7","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"8","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"yaya","evaluer"=>"Rodrigue Banzouakete","perseverance"=>"8","confiance"=>"5","collaboration"=>"5","autonomie"=>"7","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"laye","evaluer"=>"glory","perseverance"=>"9","confiance"=>"8","collaboration"=>"9","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"9","confiance"=>"9","collaboration"=>"9","autonomie"=>"9","problemsolving"=>"8","transmission"=>"8","performance"=>"9"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"laye","evaluer"=>"anta","perseverance"=>"8","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"9","transmission"=>"7","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"laye","evaluer"=>"Rodrigue Banzouakete","perseverance"=>"8","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"laye","evaluer"=>"laye","perseverance"=>"9","confiance"=>"8","collaboration"=>"9","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"1","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"6","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"1","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-07-02","evaluateur"=>"mbacke","evaluer"=>"Rodrigue Banzouakete","perseverance"=>"5","confiance"=>"3","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"0","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"7","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"anta","evaluer"=>"mbacke","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"anta","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"6","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"7","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"anta","evaluer"=>"laye","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"7","problemsolving"=>"7","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"laye","evaluer"=>"glory","perseverance"=>"9","confiance"=>"8","collaboration"=>"9","autonomie"=>"9","problemsolving"=>"9","transmission"=>"9","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"laye","evaluer"=>"mbacke","perseverance"=>"9","confiance"=>"10","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"9"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"9","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"7","transmission"=>"9","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"laye","evaluer"=>"anta","perseverance"=>"8","confiance"=>"9","collaboration"=>"9","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"laye","evaluer"=>"laye","perseverance"=>"9","confiance"=>"9","collaboration"=>"9","autonomie"=>"9","problemsolving"=>"10","transmission"=>"9","performance"=>"9"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"7","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"6","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"7","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"0","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"2","transmission"=>"3","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"3","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-12-02","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"4","transmission"=>"6","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"mah","evaluer"=>"glory","perseverance"=>"2","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"faustin","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"faustin","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"mah","evaluer"=>"mbacke","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"faustin","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"faustin","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"faustin","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"mah","evaluer"=>"yaya","perseverance"=>"2","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"mah","evaluer"=>"anta","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"mah","evaluer"=>"laye","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"laye","evaluer"=>"glory","perseverance"=>"7","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"7","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"laye","evaluer"=>"mbacke","perseverance"=>"9","confiance"=>"8","collaboration"=>"8","autonomie"=>"9","problemsolving"=>"8","transmission"=>"9","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"7","confiance"=>"9","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"9","transmission"=>"9","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"laye","evaluer"=>"anta","perseverance"=>"8","confiance"=>"8","collaboration"=>"8","autonomie"=>"7","problemsolving"=>"8","transmission"=>"9","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"laye","evaluer"=>"laye","perseverance"=>"9","confiance"=>"8","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"9","transmission"=>"8","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"glory","perseverance"=>"2","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"anta","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"ngone","evaluer"=>"laye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"1","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"2","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"0","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"abraham","evaluer"=>"glory","perseverance"=>"3","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"4","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"abraham","evaluer"=>"mbacke","perseverance"=>"6","confiance"=>"5","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"abraham","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"abraham","evaluer"=>"anta","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"fatou","evaluer"=>"glory","perseverance"=>"1","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"1"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"abraham","evaluer"=>"laye","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"fatou","evaluer"=>"yaya","perseverance"=>"1","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"3","performance"=>"1"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"fatou","evaluer"=>"anta","perseverance"=>"2","confiance"=>"0","collaboration"=>"3","autonomie"=>"1","problemsolving"=>"1","transmission"=>"3","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"fatou","evaluer"=>"laye","perseverance"=>"3","confiance"=>"2","collaboration"=>"1","autonomie"=>"2","problemsolving"=>"3","transmission"=>"1","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"0","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"mbacke","perseverance"=>"0","confiance"=>"2","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"0","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"0","confiance"=>"2","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"6","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"aissata","evaluer"=>"laye","perseverance"=>"0","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"0","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"3","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"5","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"0","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"2","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"7","collaboration"=>"6","autonomie"=>"7","problemsolving"=>"5","transmission"=>"8","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"6","confiance"=>"7","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"5","transmission"=>"8","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"7","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"7","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"7","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"glory","perseverance"=>"2","confiance"=>"6","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"mbacke","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"anta","perseverance"=>"2","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-14-02","evaluateur"=>"khady","evaluer"=>"laye","perseverance"=>"3","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"anta","evaluer"=>"mbacke","perseverance"=>"7","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"anta","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"8","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"anta","evaluer"=>"laye","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"7","problemsolving"=>"5","transmission"=>"8","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"8","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"7","transmission"=>"8","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"1","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"2","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"1","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"1","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-19-02","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-21-02","evaluateur"=>"laye","evaluer"=>"glory","perseverance"=>"0","confiance"=>"8","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-21-02","evaluateur"=>"laye","evaluer"=>"mbacke","perseverance"=>"10","confiance"=>"8","collaboration"=>"9","autonomie"=>"8","problemsolving"=>"8","transmission"=>"9","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-21-02","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"9","confiance"=>"7","collaboration"=>"8","autonomie"=>"9","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-21-02","evaluateur"=>"laye","evaluer"=>"anta","perseverance"=>"8","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-21-02","evaluateur"=>"laye","evaluer"=>"laye","perseverance"=>"9","confiance"=>"9","collaboration"=>"9","autonomie"=>"9","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-21-02","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-21-02","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"1","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-21-02","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-21-02","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"1","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-21-02","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"1","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-23-02","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-23-02","evaluateur"=>"anta","evaluer"=>"mbacke","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-23-02","evaluateur"=>"anta","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-23-02","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"7","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-23-02","evaluateur"=>"anta","evaluer"=>"laye","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-23-02","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"7","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-23-02","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-23-02","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-23-02","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-23-02","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"6","collaboration"=>"8","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"6","autonomie"=>"7","problemsolving"=>"7","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"laye","evaluer"=>"glory","perseverance"=>"8","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"7","transmission"=>"7","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"laye","evaluer"=>"mbacke","perseverance"=>"8","confiance"=>"8","collaboration"=>"7","autonomie"=>"9","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"7","confiance"=>"8","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"6","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"laye","evaluer"=>"laye","perseverance"=>"8","confiance"=>"8","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"9","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"laye","evaluer"=>"anta","perseverance"=>"8","confiance"=>"8","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"7","transmission"=>"7","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"2","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"6","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"6","transmission"=>"6","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"7","problemsolving"=>"7","transmission"=>"7","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"3","confiance"=>"3","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-26-02","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"laye","evaluer"=>"glory","perseverance"=>"8","confiance"=>"7","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"laye","evaluer"=>"mbacke","perseverance"=>"7","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"7","transmission"=>"7","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"8","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"laye","evaluer"=>"anta","perseverance"=>"9","confiance"=>"8","collaboration"=>"8","autonomie"=>"7","problemsolving"=>"9","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"laye","evaluer"=>"laye","perseverance"=>"8","confiance"=>"8","collaboration"=>"7","autonomie"=>"9","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"mbacke","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-28-02","evaluateur"=>"anta","evaluer"=>"laye","perseverance"=>"6","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"laye","evaluer"=>"glory","perseverance"=>"8","confiance"=>"8","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"9"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"laye","evaluer"=>"mbacke","perseverance"=>"8","confiance"=>"8","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"9","confiance"=>"8","collaboration"=>"8","autonomie"=>"9","problemsolving"=>"9","transmission"=>"8","performance"=>"9"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"laye","evaluer"=>"anta","perseverance"=>"8","confiance"=>"8","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"laye","evaluer"=>"laye","perseverance"=>"8","confiance"=>"8","collaboration"=>"7","autonomie"=>"8","problemsolving"=>"8","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"7","confiance"=>"5","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"6","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"7","transmission"=>"8","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-04-03","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"faustin","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"faustin","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"faustin","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"faustin","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"5","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"faustin","evaluer"=>"laye","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"2","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"7","problemsolving"=>"7","transmission"=>"7","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"7","collaboration"=>"6","autonomie"=>"7","problemsolving"=>"7","transmission"=>"8","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"glory","perseverance"=>"6","confiance"=>"5","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"aissata","evaluer"=>"laye","perseverance"=>"3","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"7","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-06-03","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"7","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"6","confiance"=>"6","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"7","transmission"=>"7","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"6","confiance"=>"7","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"7","transmission"=>"7","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-11-03","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"6","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-11-03","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-11-03","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-11-03","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-11-03","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-11-03","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"7","problemsolving"=>"7","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"7","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"7","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"7","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"4","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"4","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"7","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"mbacke","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"5","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-03","evaluateur"=>"anta","evaluer"=>"laye","perseverance"=>"6","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"1","confiance"=>"0","collaboration"=>"3","autonomie"=>"0","problemsolving"=>"0","transmission"=>"3","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"0","confiance"=>"2","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"2","transmission"=>"2","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"1","confiance"=>"0","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"3","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"2","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"2","confiance"=>"3","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"1","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"2","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"2","confiance"=>"3","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"1","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-18-03","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"1","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"2","confiance"=>"2","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"2","confiance"=>"0","collaboration"=>"5","autonomie"=>"3","problemsolving"=>"4","transmission"=>"0","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"2","confiance"=>"0","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"2","transmission"=>"0","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"6","confiance"=>"5","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"6","transmission"=>"5","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"6","confiance"=>"4","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"5","transmission"=>"4","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"3","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"3","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"5","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"laye","evaluer"=>"glory","perseverance"=>"8","confiance"=>"8","collaboration"=>"9","autonomie"=>"9","problemsolving"=>"9","transmission"=>"9","performance"=>"9"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"laye","evaluer"=>"mbacke","perseverance"=>"9","confiance"=>"9","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"9","transmission"=>"8","performance"=>"9"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"8","confiance"=>"9","collaboration"=>"9","autonomie"=>"8","problemsolving"=>"9","transmission"=>"9","performance"=>"9"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"laye","evaluer"=>"anta","perseverance"=>"8","confiance"=>"9","collaboration"=>"8","autonomie"=>"7","problemsolving"=>"9","transmission"=>"9","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-20-03","evaluateur"=>"laye","evaluer"=>"laye","perseverance"=>"9","confiance"=>"8","collaboration"=>"9","autonomie"=>"7","problemsolving"=>"9","transmission"=>"9","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"5","confiance"=>"4","collaboration"=>"6","autonomie"=>"4","problemsolving"=>"5","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"6","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"6","transmission"=>"4","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-25-03","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"laye","evaluer"=>"glory","perseverance"=>"8","confiance"=>"8","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"8","transmission"=>"7","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"laye","evaluer"=>"mbacke","perseverance"=>"8","confiance"=>"7","collaboration"=>"8","autonomie"=>"8","problemsolving"=>"7","transmission"=>"7","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"laye","evaluer"=>"yaya","perseverance"=>"8","confiance"=>"9","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"7","transmission"=>"8","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"laye","evaluer"=>"anta","perseverance"=>"8","confiance"=>"8","collaboration"=>"7","autonomie"=>"9","problemsolving"=>"7","transmission"=>"7","performance"=>"8"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"laye","evaluer"=>"laye","perseverance"=>"8","confiance"=>"7","collaboration"=>"8","autonomie"=>"7","problemsolving"=>"8","transmission"=>"7","performance"=>"7"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"6","collaboration"=>"7","autonomie"=>"7","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"3","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"2","confiance"=>"3","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-01-04","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-03-04","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-03-04","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-03-04","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-03-04","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-03-04","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-03-04","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-03-04","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"3","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-03-04","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-03-04","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"2","confiance"=>"3","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"1","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-03-04","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"3","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-08-04","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"3","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-08-04","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-08-04","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"2","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-08-04","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"2","confiance"=>"1","collaboration"=>"2","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"1"],
            ["team"=>"Team Tech&Digital","date"=>"2020-08-04","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"mah","evaluer"=>"glory","perseverance"=>"4","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"mah","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"mah","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"mah","evaluer"=>"anta","perseverance"=>"2","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"mah","evaluer"=>"laye","perseverance"=>"2","confiance"=>"2","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"faustin","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"faustin","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"faustin","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"faustin","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"faustin","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"2","confiance"=>"2","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"3","confiance"=>"2","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"2","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"2","confiance"=>"2","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"2","confiance"=>"2","collaboration"=>"3","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"0","confiance"=>"0","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"0","collaboration"=>"0","autonomie"=>"3","problemsolving"=>"4","transmission"=>"0","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-10-04","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-15-04","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"2","confiance"=>"2","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-15-04","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-15-04","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"2","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-15-04","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-15-04","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"4","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-17-04","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"3","confiance"=>"5","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-17-04","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-17-04","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-17-04","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-17-04","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-17-04","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-17-04","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"4","collaboration"=>"3","autonomie"=>"5","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-17-04","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-17-04","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-17-04","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"6","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"anta","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"7","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"6","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"anta","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"5","transmission"=>"6","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"anta","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"4","autonomie"=>"5","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"yaya","evaluer"=>"glory","perseverance"=>"1","confiance"=>"1","collaboration"=>"2","autonomie"=>"0","problemsolving"=>"1","transmission"=>"2","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"yaya","evaluer"=>"mbacke","perseverance"=>"1","confiance"=>"2","collaboration"=>"1","autonomie"=>"2","problemsolving"=>"3","transmission"=>"1","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"yaya","evaluer"=>"yaya","perseverance"=>"2","confiance"=>"3","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"yaya","evaluer"=>"anta","perseverance"=>"0","confiance"=>"1","collaboration"=>"0","autonomie"=>"2","problemsolving"=>"2","transmission"=>"0","performance"=>"1"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"yaya","evaluer"=>"laye","perseverance"=>"0","confiance"=>"0","collaboration"=>"0","autonomie"=>"0","problemsolving"=>"0","transmission"=>"0","performance"=>"0"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"2","confiance"=>"3","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"3","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"5","transmission"=>"5","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"1","transmission"=>"2","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-22-04","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"2","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"2","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-24-04","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-24-04","evaluateur"=>"anta","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-24-04","evaluateur"=>"anta","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-24-04","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-24-04","evaluateur"=>"anta","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-24-04","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"4","transmission"=>"2","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-24-04","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"2","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-24-04","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"4","confiance"=>"3","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"4","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-24-04","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"2","confiance"=>"2","collaboration"=>"2","autonomie"=>"2","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-24-04","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"3","confiance"=>"3","collaboration"=>"1","autonomie"=>"2","problemsolving"=>"2","transmission"=>"2","performance"=>"2"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"anta","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"anta","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"anta","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"anta","evaluer"=>"laye","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"2","confiance"=>"4","collaboration"=>"3","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"4","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"3","collaboration"=>"3","autonomie"=>"5","problemsolving"=>"3","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"3","confiance"=>"2","collaboration"=>"2","autonomie"=>"3","problemsolving"=>"3","transmission"=>"2","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-29-04","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"2","confiance"=>"1","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"2","transmission"=>"2","performance"=>"1"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"glory","evaluer"=>"glory","perseverance"=>"6","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"glory","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"6","problemsolving"=>"5","transmission"=>"4","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"glory","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"5","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"5","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"glory","evaluer"=>"anta","perseverance"=>"5","confiance"=>"4","collaboration"=>"5","autonomie"=>"5","problemsolving"=>"4","transmission"=>"5","performance"=>"5"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"glory","evaluer"=>"laye","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"3"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"mbacke","evaluer"=>"glory","perseverance"=>"3","confiance"=>"4","collaboration"=>"4","autonomie"=>"3","problemsolving"=>"3","transmission"=>"3","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"mbacke","evaluer"=>"mbacke","perseverance"=>"4","confiance"=>"4","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"mbacke","evaluer"=>"yaya","perseverance"=>"3","confiance"=>"4","collaboration"=>"3","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"mbacke","evaluer"=>"anta","perseverance"=>"4","confiance"=>"5","collaboration"=>"4","autonomie"=>"4","problemsolving"=>"4","transmission"=>"4","performance"=>"4"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"mbacke","evaluer"=>"laye","perseverance"=>"3","confiance"=>"2","collaboration"=>"1","autonomie"=>"1","problemsolving"=>"1","transmission"=>"1","performance"=>"1"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"anta","evaluer"=>"glory","perseverance"=>"5","confiance"=>"7","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"7","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"anta","evaluer"=>"mbacke","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"anta","evaluer"=>"yaya","perseverance"=>"5","confiance"=>"6","collaboration"=>"7","autonomie"=>"6","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"anta","evaluer"=>"anta","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"6","problemsolving"=>"6","transmission"=>"7","performance"=>"6"],
            ["team"=>"Team Tech&Digital","date"=>"2020-13-05","evaluateur"=>"anta","evaluer"=>"laye","perseverance"=>"5","confiance"=>"6","collaboration"=>"6","autonomie"=>"5","problemsolving"=>"6","transmission"=>"6","performance"=>"6"],
       ];
        for ($i=0; $i <count($allevalion) ; $i++) { 
            $evaluation= new Evaluation();
            $a=$allevalion[$i]['evaluateur'];
            $b=$allevalion[$i]['evaluer'];
            $evaluateur=$userRepository->findOneBy(['username'=>$a]);
            $evaluer=$userRepository->findOneBy(['username'=>$b]);
            $evaluation->setEvaluateur($evaluateur);
            $evaluation->setEvaluer($evaluer);
            $evaluation->setPerseverance($allevalion[$i]['perseverance']);
            $evaluation->setConfiance($allevalion[$i]['confiance']);
            $evaluation->setCollaboration($allevalion[$i]['collaboration']);
            $evaluation->setAutonomie($allevalion[$i]['autonomie']);
            $evaluation->setProblemsolving($allevalion[$i]['problemsolving']);
            $evaluation->setTransmission($allevalion[$i]['transmission']);
            $evaluation->setPerformance($allevalion[$i]['performance']);
            $evaluation->setTeam($allevalion[$i]['team']);
            $rien=$allevalion[$i]['date'];
            $alpha=$allsessionRepository->findOneBy(['date'=>$rien]);
            $evaluation->setSession($alpha);
            $entityManagerInterface->persist($evaluation);
        }
        $entityManagerInterface->flush();
        return $this->json([
            'message' => 'Ajout Effectuer',
            'status' => 200
        ]);
    }
}
