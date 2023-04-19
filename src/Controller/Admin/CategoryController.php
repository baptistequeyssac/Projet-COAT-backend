<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="app_admin")
 * @package App\Controller\Admin
 */
class CategoriesController extends AbstractController
{
    /**
    * @Route("/", name="home")
    */

    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
        /**
        * @Route("/category/ajout", name="categories_ajout")
        */
        public function ajoutCategorie(Request $request)
        {
            $categorie = new Categories;
            $form = $this->createForm(CategoriesType::class, $Categories);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($Categories);
                $em->flush();

                return $this->redirectToRoute('admin_home');
            }
            return $this->render('admin/Categories/ajout.html.twig',[
                'form' => $form->createView()
            ]);
        }
}
