<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Country;
use AppBundle\Entity\CapitalCity;

class LoadCountryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $country = (new Country())
            ->setName('Japan')
        ;
        $manager->persist($country);

        $capitalCity = (new CapitalCity())
            ->setName('Tokyo')
            ->setCountry($country)
        ;
        $manager->persist($capitalCity);

        $manager->flush();
    }
}
