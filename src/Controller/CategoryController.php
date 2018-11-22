<?php
/**
 * Created by PhpStorm.
 * User: mcnitch
 * Date: 19/11/18
 * Time: 10:15
 */

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="add_category")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }
        return $this->render('category/category.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/category/{id}", name="category_id")
     * @param Category $category
     */
    public function show(Category $category): Response
    {
        $articles = $category->getArticles();
        return $this->render('category/categoryId.html.twig', ['category' => $category, 'articles' => $articles]);
    }
}