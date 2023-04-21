<?php

namespace App\Controller\Backoffice;

use App\Entity\Organizer;
use App\Form\OrganizerType;
use App\Repository\OrganizerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/organizer")
 */
class OrganizerController extends AbstractController
{
    /**
     * @Route("/", name="app_backoffice_organizer_index", methods={"GET"})
     */
    public function index(OrganizerRepository $organizerRepository): Response
    {
        return $this->render('backoffice/organizer/index.html.twig', [
            'organizers' => $organizerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_backoffice_organizer_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OrganizerRepository $organizerRepository): Response
    {
        $organizer = new Organizer();
        $form = $this->createForm(OrganizerType::class, $organizer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $organizerRepository->add($organizer, true);

            return $this->redirectToRoute('app_backoffice_organizer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/organizer/new.html.twig', [
            'organizer' => $organizer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_organizer_show", methods={"GET"})
     */
    public function show(Organizer $organizer): Response
    {
        return $this->render('backoffice/organizer/show.html.twig', [
            'organizer' => $organizer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_organizer_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Organizer $organizer, OrganizerRepository $organizerRepository): Response
    {
        $form = $this->createForm(OrganizerType::class, $organizer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $organizerRepository->add($organizer, true);

            return $this->redirectToRoute('app_backoffice_organizer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/organizer/edit.html.twig', [
            'organizer' => $organizer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_organizer_delete", methods={"POST"})
     */
    public function delete(Request $request, Organizer $organizer, OrganizerRepository $organizerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organizer->getId(), $request->request->get('_token'))) {
            $organizerRepository->remove($organizer, true);
        }

        return $this->redirectToRoute('app_backoffice_organizer_index', [], Response::HTTP_SEE_OTHER);
    }
}
