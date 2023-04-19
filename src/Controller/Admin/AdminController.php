<?php

namespace App\Controller\Admin;

use App\Entity\Artist;
use App\Form\ArtistType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="app_admin")
 * @package App\Controller\Admin
 */
class AdminController extends AbstractController
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
        * @Route("/artist/ajout", name="artist_ajout")
        */
        public function ajoutCategorie(Request $request)
        {
            $categorie = new artist;
            $form = $this->createForm(artistType::class, $artist);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($artist);
                $em->flush();

                return $this->redirectToRoute('admin_home');
            }
            return $this->render('admin/artist/ajout.html.twig',[
                'form' => $form->createView()
            ]);
        }
}
