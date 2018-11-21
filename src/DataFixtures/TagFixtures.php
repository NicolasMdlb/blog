<?php
/**
 * Created by PhpStorm.
 * User: mcnitch
 * Date: 21/11/18
 * Time: 17:56
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Tag;

class TagFixtures extends Fixture
{
    private $tags = [
        'developement',
        'news',
        'blabla',
        ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->tags as $key => $tagName){
            $tag = new Tag();
            $tag->setName($tagName);
            $manager->persist($tag);
            $this->addReference('tag_' . $key, $tag);
        }
        $manager->flush();
    }
}
