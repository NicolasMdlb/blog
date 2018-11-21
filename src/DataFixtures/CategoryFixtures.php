<?php
/**
 * Created by PhpStorm.
 * User: mcnitch
 * Date: 21/11/18
 * Time: 09:23
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    private $categories = [
            'PHP',
            'JavaScript',
            'Java',
            'Ruby',
            'Kotlin'
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->categories as $key => $categoryName){
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $this->addReference('categorie_' . $key, $category);
        }
        $manager->flush();
    }
}
