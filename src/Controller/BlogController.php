<?php
/**
 * Created by PhpStorm.
 * User: mcnitch
 * Date: 12/11/18
 * Time: 15:49
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use App\Entity\Article;
use App\Entity\Tag;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Slugify;

class BlogController extends AbstractController
{
    /**
     * Show all row from article's entity
     *
     * @Route("/blog", name="blog_index")
     * @return Response A response instance
     */
    public function index(
        Request $request,
        Slugify $slugify,
        \Swift_Mailer $mailer
    ): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        $article = new article;
        $form = $this->createForm(ArticleType::class, $article, ['method' => Request::METHOD_GET])
            ->add('title', TextType::class)
            ->add('content', TextType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $article = $form->getData();
            $article->setSlug($slugify->generate($article->getTitle()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $message = (new \Swift_Message('Un nouvel article vient d\'être publié !'))
                ->setFrom('nicolas.mdlb@gmail.com')
                ->setTo('nicolas.mdlb@gmail.com')
                ->setBody($this->renderView('email/notification.html.twig', ['article' => $article]),
            'text/html');
            $mailer->send($message);
            return $this->render('blog/index.html.twig', ['articles' => $articles, 'form' => $form->createView(),]);
        }
        return $this->render('blog/index.html.twig', ['articles' => $articles, 'form' => $form->createView(),]);
    }

    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/blog/{id}-{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     * @return Response A response instance
     */
    public function show(Article $article): Response
    {
        $tags = $article->getTags();

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with ' . $article . ' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'tags' => $tags
            ]
        );
    }

    /**
     * @Route("/blog/category/{categoryName}/all", name="blog_showcat")
     * @return Response A response instance
     */
    public function showAllByCategory($categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName($categoryName);

        $articles = $category->getArticles();

        return $this->render(
            'blog/showcat.html.twig',
            ['articles' => $articles,
                'category' => $category]
        );
    }

    /**
     * @Route("/blog/category/{categoryName}", name="category")
     * @return Response A response instance
     */
    public function showByCategory(String $categoryName)
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName($categoryName);

        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 3);

        return $this->render(
            'blog/showcat.html.twig',
            ['articles' => $articles,
                'category' => $category]
        );
    }

    /**
     * @Route("blog/tag/{tagName}", name="blog_tag")
     * @return Response A response instance
     */
    public function showByTag(String $tagName): Response
    {
        $tag = $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findOneByName($tagName);

        $articles = $tag->getArticles();

        return $this->render(
            'blog/tag.html.twig',
            ['articles' => $articles,
                'tag' => $tag]
        );
    }
}
