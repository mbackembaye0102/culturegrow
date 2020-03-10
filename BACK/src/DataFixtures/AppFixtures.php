<?php

namespace App\DataFixtures;

use App\Entity\Structure;
use App\Entity\TeamPromo;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $structure= new Structure();
        $structure->setNom("GROW");
        $manager->persist($structure);
        $listteam=["Team Business","Grow Academy","Team Créa","Team Tech&Digital"];
        for ($i=0; $i < count($listteam); $i++) { 
            $team= new TeamPromo();
            $team->setNom($listteam[$i]);
            $team->setStructure($structure);
            $manager->persist($team);
        }
        $user= new User();
        $user->setPrenom("El Hadji Yaya");
        $user->setNom("LY");
        $user->setUsername("yalya");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setStatut("actif");
        $user->setTelephone("772652363"); 
        $user->setTeampromo($team);
        $password = $this->encoder->encodePassword($user, 'welcome');
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();
    }
}
