<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use Faker;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // create 20 article! Bam!
        for ($i = 0; $i < 20; $i++) {
            $article = new Article();
            $article->setTitle($faker->catchPhrase() . ' no° ' . $i)
                ->setDescription(implode($faker->paragraphs(2)))
                ->setText(implode($faker->paragraphs(6)))
                ->setStatus(0)
                ->setCategorie(mt_rand(0,2))
                ->setImage($faker->imageUrl(400,250));

            $manager->persist($article);
        }
        $manager->flush();
    }
}
