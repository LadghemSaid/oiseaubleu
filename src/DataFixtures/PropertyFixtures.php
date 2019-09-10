<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use Faker;
use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // create 20 propertys! Bam!
        for ($i = 0; $i < 20; $i++) {
            $property = new Property();
            $property->setTitle($faker->catchPhrase() . ' no° ' . $i)
                ->setPrice(mt_rand(10000, 1000000))
                ->setRooms(mt_rand(1, 6))
                ->setDescription(implode($faker->paragraphs(6)))
                ->setSurface(mt_rand(9, 300))
                ->setFloor(mt_rand(0, 15))
                ->setBedrooms(mt_rand(1, 10))
                ->setSold($faker->boolean())
                ->setGarage($faker->boolean())
                ->setHeat(mt_rand(1, 2))
                ->setType(mt_rand(1, 2))
                ->setCity($faker->city())
                ->setAddress($faker->streetAddress())
                ->setPostalCode($faker->postcode())
                ->setImage($faker->imageUrl(400,250));

            $manager->persist($property);
        }
        $manager->flush();
    }
}