<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;



class EventController extends AbstractController
{
    /**
     * @Route("/api/events", name="app_api_event", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all events",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Event::class, groups={"event_browse"}))
     *      )    
     * )
     */

    //* Return all events
    public function browse(EventRepository $eventRepository): JsonResponse
    {
        $allEvent = $eventRepository->findAll();

        return $this->json(
            $allEvent,
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    "event_browse"
                ]
            ]
        );

    }

    /**
     * @Route("/api/events", name="app_api_event_add", methods={"POST"})
     * 
     * @OA\RequestBody(
     *     @Model(type=EventType::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="new created event",
     *     @OA\JsonContent(
     *          ref=@Model(type=Event::class, groups={"event_read", "artist_add", "category_read", "organizer_add", "type_read", "region_read"})
     *      )
     * )
     * 
     * @OA\Response(
     *     response=422,
     *     description="NotEncodableValueException"
     * )
     */

     //* Add an event
     public function add(
        Request $request,
        SerializerInterface $serializer,
        EventRepository $eventRepository,
        ValidatorInterface $validator
        )
     {
        $contentJson = $request->getContent();

        try {
            $eventFromJson = $serializer->deserialize(
                $contentJson,
                Event::class,
                'json'
            );
        } catch (\Throwable $e){
            return $this->json(
                $e->getMessage(),
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $listError = $validator->validate($eventFromJson);

        if (count($listError) > 0){
            // we have errors
            return $this->json(
                $listError,
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // persist + flush
        $eventRepository->add($eventFromJson, true);

        // inform user
        return $this->json(
            $eventFromJson,
            // code 201
            Response::HTTP_CREATED,
            [],
            [
                "groups" =>
                [
                    "event_browse",
                    "event_read",
                    "artist_add",
                    "category_read",
                    "organizer_add",
                    "type_read",
                    "region_read"
                ]
            ]
                );
     }

     /**
      * @Route("/api/events/{id}", name="app_api_event_edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
      */

      //* Edit/update an event
     public function edit(
        Event $event = null,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
     )
     {
        if ($event === null) {
            // paramConverter dont found the entity : code 404
            return $this->json("Evénement non trouvé", Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $request->getContent();

        try {
            $serializer->deserialize(
                $jsonContent,
                Event::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $event]
            );
        } catch (\Throwable $e){
            return $this->json(
                $e->getMessage(),
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $listError = $validator->validate($event);

        if (count($listError) > 0) {
            return $this->json(
                $listError,
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // flush 
        $entityManager->flush();

        return $this->json(
            null,
            // code 204
            Response::HTTP_NO_CONTENT
        );
     }

     /**
      * @Route("/api/events/{id}", name="app_api_event_read", methods={"GET"}, requirements={"id"="\d+"})
      */

      //* Read an event
      public function read(Event $event = null)
      {
        // our user give a bad ID, We give a 404
        if ($event === null){
            return $this->json(
                [
                    "message" => "Oups, il semblerait que cet événement n'existe pas"
                ],
                // code 404
                Response::HTTP_NOT_FOUND
            );
        }

         return $this->json(
            $event,
            // code 200
            Response::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "event_read",
                    "artist_read",
                    "category_read",
                    "organizer_read",
                    "type_read",
                    "region_read"
                ]
            ]
        );
      }

      /**
       * @Route("/api/events/{id}", name="app_api_event_delete", methods={"DELETE"}, requirements={"id"="\d+"})
       */

       //* Delete an event
       public function delete (Event $event = null, EventRepository $eventRepository)
       {
        if ($event === null){
            // paramConverter not found : code 404
            return $this->json("Evénement non trouvé", Response::HTTP_NOT_FOUND);
        }

        // delete
        $eventRepository->remove($event, true);

        return $this->json(
            null,
            // code 204
            Response::HTTP_NO_CONTENT
        );
       }




}

