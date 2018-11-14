<?php
/**
 * Created by PhpStorm.
 * User: mcnitch
 * Date: 12/11/18
 * Time: 15:49
 */

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog_list")
     */
    public function list()
    {
        return $this->render('blog/index.html.twig');
    }

    /**
     * Show all row from article's entity
     *
     * @Route("/", name="blog_index")
     * @return Response A response instance
     */
    public function index() : Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     *  @return Response A response instance
     */
    public function show($slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace('/-/',
                            ' ', ucwords(trim(strip_tags($slug)),
                            "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug' => $slug,
            ]
        );
    }

    /**
     * @Route("/category/{category}", name="category")
     * @return Response A response instance
     */
    public function showByCategory(String $category)
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            -> findOneByName($category);

        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            -> findBy(['category' => $category], ['id' => 'DESC'], 3);

        return $this->render(
            'blog/category.html.twig',
            ['articles' => $articles,
                'category' => $category]
        );
    }
}
