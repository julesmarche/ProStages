<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Generation des utilisateur
        $jules=new User();
        $jules->setPrenom("Jules");
        $jules->setNom("Marche");
        $jules->setEmail("jmarchepro@gmail.com");
        $jules->setRoles(['ROLE_ADMIN']);
        $jules->setPassword("$2y$10$5VI4N9E9Ww5Oj26fNJ7amuwKu0RCptyD7GY3P8zC18voNbOL5iWHK");
        $manager->persist($jules);

        $kevin=new User();
        $kevin->setPrenom("Kevin");
        $kevin->setNom("Garel");
        $kevin->setEmail("kgarel@gmail.com");
        $kevin->setRoles(['ROLE_USER']);
        $kevin->setPassword("$2y$10$5VI4N9E9Ww5Oj26fNJ7amuwKu0RCptyD7GY3P8zC18voNbOL5iWHK");
        $manager->persist($kevin);



        //Création générateur de données faker
        $faker = \Faker\Factory::create('fr_FR');

        // Création formations
        $dutInfo = new Formation();
        $dutInfo->setNomLong("DUT Informatique");
        $dutInfo->setNomCourt("DUT Info");

        $dutInfoImagNum = new Formation();
        $dutInfoImagNum->setNomLong("DUT Informatique et Imagerie Numérique");
        $dutInfoImagNum->setNomCourt("DUT IIM");

        $dutGea = new Formation();
        $dutGea->setNomLong("DUT Gestion des entreprises et des administrations");
        $dutGea->setNomCourt("DUT GEA");

        $lpProg = new Formation();
        $lpProg->setNomLong("Licence programmation");
        $lpProg->setNomCourt("LP");

        $dutGenieLogiciel = new Formation();
        $dutGenieLogiciel->setNomLong("DUT Genie Logiciel");
        $dutGenieLogiciel->setNomCourt("DUT GL");


        $tableauFormations=array($dutInfo, $dutInfoImagNum, $dutGea, $lpProg, $dutGenieLogiciel);

        //Enregistrement et vérification
        foreach($tableauFormations as $formation)
        {
            $manager->persist($formation);
        }


        //Création des entreprises
        

        for($i=0 ; $i<15 ; $i++)
        {
            $entreprise = new Entreprise();
            $entreprise->setActivite($faker->realText($maxNbChars = 50, $indexSize = 2));
            $entreprise->setAdresse($faker->address);
            $entreprise->setNom($faker->company);
            $entreprise->setURLsite($faker->url);
            
            //Je range les entreprises dans un tableau
            $entreprises[]=$entreprise;
            $manager->persist($entreprise);

        }
        

        
        for($i=0 ; $i<30 ; $i++)
        {
            //Génération d'un nombre aléatoire pour associé une entreprise à ce stage
            $entrepriseAssocieAuStage = $faker->numberBetween($min=0 , $max=14);
            
            //Génération d'un nombre aléatoire pour avoir le nombre de formation de ce stage
            $nombreDeFormations = $faker->numberBetween($min=1, $max=3);

            $stage = new Stage();
            $stage->setTitre($faker->realText($maxNbChars = 50, $indexSize = 2));
            $stage->setDescriptionMissions($faker->realtext());
            $stage->setEmailContact($faker->email);
            
            //On associe l'entreprise qui a le numéro tiré juste avant au stage
            $stage->setEntreprise($entreprises[$entrepriseAssocieAuStage]);
            
            for($j=0 ; $j<$nombreDeFormations ; $j++)
            {
                //On associe une formation aléatoirement
                $formationAssocieeAuStage = $faker->unique()->numberBetween($min=0, $max=4);
                $stage->addTypeFormation($tableauFormations[$formationAssocieeAuStage]);
            }
            $faker->unique($reset = true);
            
            $manager->persist($stage);
        }
        
        $manager->flush();
    }
}
