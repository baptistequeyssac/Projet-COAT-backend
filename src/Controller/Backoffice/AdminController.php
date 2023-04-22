<?php

namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/admin", name="app_backoffice_admin")
 * //@package App\Controller\Admin
 */

class AdminController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('backoffice/admin/index.html.twig', [
            'amdin' => 'AdminController',
        ]);
    }
}