<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Comment;
use Faker;
use App\Entity\Categorie;
use App\Entity\User;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Msalsas\VotingBundle\Service\Voter;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $userArray= array();
        $categorieArray= array();

        //Creation de l'admin user
        $user = new User();
        $user->setEmail("aze@aze.fr");
        $user->setUsername($faker->userName);
        $user->setPassword(password_hash("azeaze",PASSWORD_BCRYPT));
        array_push($userArray,$user);
        $manager->persist($user);


        // create 10 Users! Bam!
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail("aze".$i."@aze.fr");
            $user->setUsername($faker->userName);
            $user->setPassword(password_hash("azeaze",PASSWORD_BCRYPT));
            array_push($userArray,$user);
            $manager->persist($user);
        }

        //create 10 Categories !
        for ($i = 0; $i <= 10; $i++) {
            $categorie = new Categorie();
            $categorie->setName($faker->colorName);
            $categorie->setCreatedAt($faker->dateTimeBetween("-10days","now"));
            array_push($categorieArray,$categorie);
            $manager->persist($categorie);
            $manager->flush();

        }

        // create 4 Articles Per user and 4 comments Per Article! Bam!
        $compteur= 0;
        for ($i = 0; $i < sizeof($userArray); $i++) {
            for ($j = 0; $j <= 4; $j++) {
                $article = new Article();
                $article->setTitle($faker->catchPhrase() . ' no° ' . $compteur++)
                    ->setDescription(implode($faker->paragraphs(2)))
                    ->setText(implode($faker->paragraphs(15)))
                    ->setStatus(0)
                    ->setAuthor($userArray[$i])
                    ->addCategorie($categorieArray[array_rand($categorieArray)])
                    ->setCreatedAt($date = $faker->dateTimeBetween("-10days","now"))
                    ->setImage($faker->imageUrl(900,900));
                $manager->persist($article);


                //creation des commentaires
                for ($k = 0; $k <= 4; $k++) {
                $comment = new Comment();
                $comment->setTextComment($faker->paragraph(2))
                    ->setUser($userArray[$i])
                    ->setArticle($article)
                    ->setApproved(true)
                    ->setCreatedAt($faker->dateTimeBetween($date,"now"));
                $manager->persist($comment);
                }
            }

        }
        $manager->flush();



    }
}
