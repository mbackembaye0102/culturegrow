<?php

namespace App\DataFixtures;

use App\Entity\Structure;
use App\Entity\TeamPromo;
use App\Entity\UserTeamPromo;
use App\Entity\User;
use App\Entity\Poste;
use App\Repository\TeamPromoRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder,TeamPromoRepository $teamPromoRepository)
    {
        $this->encoder = $encoder;
        $this->teamPromoRepository = $teamPromoRepository;
    }
    public function load(ObjectManager $manager)
    {
        $structure= new Structure();
        $structure->setNom("GROW");
        $structure->setImage("defaut.png");
        $manager->persist($structure);
        $listteam=["Team Business","Grow Academy","Team Créa","Team Tech&Digital"];
        for ($i=0; $i < count($listteam); $i++) { 
            $team= new TeamPromo();
            $team->setNom($listteam[$i]);
            $team->setStructure($structure);
            $team->setImage("defaut.png");
            $manager->persist($team);
        }
        $manager->flush();
        //SUPER ADMIN
        $user= new User();
        $user->setPrenom("Babacar");
        $user->setNom("SY");
        $user->setUsername("director");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setStatut("actif");
        $user->setTelephone("0000000");
        $user->setPoste("Directeur");
        $user->setImage("defaut.png");
        $password = $this->encoder->encodePassword($user, 'welcome');
        $user->setPassword($password);
        $manager->persist($user);
        $listposte=["Développeur","Project Manager","Assitante Project Manager","Monteur","Cadreur","Infographe"];
        for ($i=0; $i < count($listteam); $i++) { 
            $poste= new Poste();
            $poste->setNom($listposte[$i]);
            $manager->persist($poste);
        }
        $manager->flush();
        // $alluser=[
        //     0=>['Prenom'=>'','Nom'=>'','Username'=>'','Telephone'=>'','Poste'=>''],
        
        // ];
        $alluser=[
            0=>['Prenom'=>'El Hadji Yaya','Nom'=>'Ly','Username'=>'yaya'],
            1=>['Prenom'=>'Elhadji Mbacké','Nom'=>'Mbaye','Username'=>'mbacke'],
            4=>['Prenom'=>'Adji Anta','Nom'=>'Dabo','Username'=>'anta'],
            5=>['Prenom'=>'Rodrigue','Nom'=>'Banzoua-ketti','Username'=>'rodrigue'],
            6=>['Prenom'=>'Abdoulaye','Nom'=>'Ndiaye','Username'=>'laye'],
            7=>['Prenom'=>'Glory AYIVI','Nom'=>'AMAH','Username'=>'Glory'],
            8=>['Prenom'=>'Abdoulaye','Nom'=>'Faye','Username'=>'abdoulaye'],
            9=>['Prenom'=>'Jean Jacques Faustin','Nom'=>'Badji','Username'=>'faustin'],
            10=>['Prenom'=>'Abraham Ibrahima','Nom'=>'Gomis','Username'=>'abraham'],
            11=>['Prenom'=>'Aissata','Nom'=>'Déme','Username'=>'aissata'],
            12=>['Prenom'=>'Fatou','Nom'=>'Ndaw','Username'=>'fatou'],
            13=>['Prenom'=>'Yacine','Nom'=>'Sémbéne','Username'=>'yacine'],
            14=>['Prenom'=>'Mame Khady','Nom'=>'Pouye','Username'=>'khady'],
            15=>['Prenom'=>'Mah Savané','Nom'=>'Keita','Username'=>'mah'],
            16=>['Prenom'=>'Ngoné','Nom'=>'Manga','Username'=>'ngone'],
        ];
        $critere=['evaluer','evaluateur','perseverance','confiance','collaboration','autonomie','problemsolving','transmission','performance'];
        // $allevaluation=[
        //     0=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>''],
        // ];
        $allevaluationYaya=[
            0=>['evaluer'=>'','evaluateur'=>'yaya','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>'2020/05/02'],
            1=>['evaluer'=>'','evaluateur'=>'yaya','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>'2020/05/02'],
            2=>['evaluer'=>'','evaluateur'=>'yaya','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>'2020/05/02'],
            3=>['evaluer'=>'','evaluateur'=>'yaya','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>'2020/05/02'],
            4=>['evaluer'=>'','evaluateur'=>'yaya','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>'2020/05/02'],
            5=>['evaluer'=>'','evaluateur'=>'yaya','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>'2020/05/02'],
            6=>['evaluer'=>'','evaluateur'=>'yaya','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>'2020/05/02'],
            7=>['evaluer'=>'','evaluateur'=>'yaya','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>'2020/05/02'],
            8=>['evaluer'=>'','evaluateur'=>'yaya','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>'2020/05/02'],
            9=>['evaluer'=>'','evaluateur'=>'yaya','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>'2020/05/02'],
            // 10=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 11=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 12=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 13=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 14=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 15=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 16=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 17=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 18=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 19=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 20=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 21=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 22=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 23=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 24=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 25=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 26=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 27=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 28=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 29=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 30=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 31=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
            // 32=>['evaluer'=>'','evaluateur'=>'','perseverance'=>'','confiance'=>'','collaboration'=>'','autonomie'=>'','problemsolving'=>'','transmission'=>'','performance'=>'','date'=>''],
        ];
    }
}
