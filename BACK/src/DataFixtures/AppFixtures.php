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
        //yaya user
        $user= new User();
        $user->setPrenom("El Hadji Yaya");
        $user->setNom("Ly");
        $user->setUsername("yaya");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setStatut("actif");
        $user->setTelephone("772652363");
        $user->setPoste("Développeur");
        $user->setImage("defaut.png");
        $user->setStructure($structure);
        $password = $this->encoder->encodePassword($user, 'welcome');
        $user->setPassword($password);
        $manager->persist($user);
        $userTeamPromo= new UserTeamPromo();
        $userTeamPromo->setUser($user);
        $userTeamPromo->setTeamPromo($team);
        $manager->persist($userTeamPromo);
        // mbacké user
        $user1= new User();
        $user1->setPrenom("ElHadji Mbacke");
        $user1->setNom("Mbaye");
        $user1->setUsername("mbacke");
        $user1->setRoles(["ROLE_ADMIN"]);
        $user1->setStatut("actif");
        $user1->setTelephone("772658952");
        $user1->setPoste("Développeur");
        $user1->setImage("defaut.png");
        $user1->setStructure($structure);
        $password1 = $this->encoder->encodePassword($user1, 'welcome');
        $user1->setPassword($password1);
        $manager->persist($user1);
        $userTeamPromo0= new UserTeamPromo();
        $userTeamPromo0->setUser($user1);
        $userTeamPromo0->setTeamPromo($team);
        $manager->persist($userTeamPromo0);
        $userTeamPromo1= new UserTeamPromo();
        $userTeamPromo1->setUser($user1);
        $team1=$this->teamPromoRepository->findOneBy(['nom'=>'Team Business']);
        $userTeamPromo1->setTeamPromo($team1);

        $manager->persist($userTeamPromo1);
        $listposte=["Développeur","Project Manager","Assitante Project Manager","Monteur","Cadreur","Infographe"];
        for ($i=0; $i < count($listteam); $i++) { 
            $poste= new Poste();
            $poste->setNom($listposte[$i]);
            $manager->persist($poste);
        }
        $manager->flush();
    }
}
