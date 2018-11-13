<?php
/**
 * Created by PhpStorm.
 * User: mcnitch
 * Date: 12/11/18
 * Time: 15:49
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("blog/{slug<[a-z0-9-]+>?article-sans-titre}", name="blog_show")
     */
    public function show($slug)
    {
        $result = ucwords(str_replace('-',' ',$slug));
        return $this->render('blog/article.html.twig', ['slug' => $result]);
    }

    /**
     * @Route("/blog", name="blog_list")
     */
    public function list()
    {
        return $this->render('blog/index.html.twig');
    }


}