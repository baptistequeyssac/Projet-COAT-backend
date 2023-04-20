<?php

namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeBackofficeController extends AbstractController
{
    /**
     * @Route("/backoffice/home", name="app_backoffice_home_backoffice")
     */
    public function index(): Response
    {
        return $this->render('backoffice/home_backoffice/index.html.twig', [
            'controller_name' => 'HomeBackofficeController',
        ]);
    }
}
