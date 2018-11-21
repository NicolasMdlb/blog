<?php
/**
 * Created by PhpStorm.
 * User: mcnitch
 * Date: 21/11/18
 * Time: 09:23
 */

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use Faker;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++ ) {
            $article = new Article();
            $article->setTitle(strtolower($faker->sentence()));
            $article->setContent($faker->text);
            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_' . rand(0, 3)));
        }
            $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}
