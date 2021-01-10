<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Formation;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $dutInfo = new Formation();
        $dutInfo->setNom("DUT Informatique");
        $dutInfo->setDescription("Le DUT Informatique a pour but de former des informaticiens généralistes de niveau Bac+2 au travers d'une solide formation théorique et pratique.");

        $manager->persist($dutInfo);
        $manager->flush();
    }
}
