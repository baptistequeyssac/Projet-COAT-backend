<?php

namespace App\Controller\Backoffice;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="app_backoffice_event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('backoffice/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_backoffice_event_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->add($event, true);

            return $this->redirectToRoute('app_backoffice_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('backoffice/event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_event_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(Event1Type::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->add($event, true);

            return $this->redirectToRoute('app_backoffice_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_event_delete", methods={"POST"})
     */
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('app_backoffice_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
