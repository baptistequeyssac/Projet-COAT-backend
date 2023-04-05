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
     * @Route("/api/Event", name="app_api_Event", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all Events",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Event::class, groups={"Event_browse"}))
     *      )    
     * )
     */

    //* Return all Events
    public function browse(EventRepository $EventRepository): JsonResponse
    {
        $allEvent = $EventRepository->findAll();

        return $this->json(
            $allEvent,
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    "Event_browse"
                ]
            ]
        );

    }

    /**
     * @Route("/api/Events", name="app_api_Event_add", methods={"POST"})
     * 
     * @OA\RequestBody(
     *     @Model(type=EventType::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="new created Event",
     *     @OA\JsonContent(
     *          ref=@Model(type=Event::class, groups={"Event_read", "event_read", "category_read", "organizer_read"})
     *      )
     * )
     * 
     * @OA\Response(
     *     response=422,
     *     description="NotEncodableValueException"
     * )
     */

     //* Add an Event
     public function add(
        Request $request,
        SerializerInterface $serializer,
        EventRepository $EventRepository,
        ValidatorInterface $validator
        )
     {
        $contentJson = $request->getContent();

        try {
            $EventFromJson = $serializer->deserialize(
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

        $listError = $validator->validate($EventFromJson);

        if (count($listError) > 0){
            // we have errors
            return $this->json(
                $listError,
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // persist + flush
        $EventRepository->add($EventFromJson, true);

        // inform user
        return $this->json(
            $EventFromJson,
            // code 201
            Response::HTTP_CREATED,
            [],
            [
                "groups" =>
                [
                    "Event_read",
                    "event_read",
                    "category_read",
                    "organizer_read"
                ]
            ]
                );
     }

     /**
      * @Route("/api/Events/{id}", name="app_api_Event_edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
      */

      //* Edit/update an Event
     public function edit(
        Event $Event = null,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
     )
     {
        if ($Event === null) {
            // paramConverter dont found the entity : code 404
            return $this->json("Evente non trouvé", Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $request->getContent();

        try {
            $serializer->deserialize(
                $jsonContent,
                Event::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $Event]
            );
        } catch (\Throwable $e){
            return $this->json(
                $e->getMessage(),
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $listError = $validator->validate($Event);

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
      * @Route("/api/Events/{id}", name="app_api_Event_read", methods={"GET"}, requirements={"id"="\d+"})
      */

      //* Read an Event
      public function read(Event $Event = null)
      {
        // our user give a bad ID, We give a 404
        if ($Event === null){
            return $this->json(
                [
                    "message" => "Oups, il semblerait que cet Evente n'existe pas"
                ],
                // code 404
                Response::HTTP_NOT_FOUND
            );
        }

         return $this->json(
            $Event,
            // code 302
            Response::HTTP_FOUND,
            [],
            [
                "groups" =>
                [
                    "Event_read",
                    "event_read",
                    "category_read",
                    "organizer_read"
                ]
            ]
        );
      }

      /**
       * @Route("/api/Events/{id}", name="app_api_Event_delete", methods={"DELETE"}, requirements={"id"="\d+"})
       */

       //* Delete an Event
       public function delete (Event $Event = null, EventRepository $EventRepository)
       {
        if ($Event === null){
            // paramConverter not found : code 404
            return $this->json("Evente non trouvé", Response::HTTP_NOT_FOUND);
        }

        // delete
        $EventRepository->remove($Event, true);

        return $this->json(
            null,
            // code 204
            Response::HTTP_NO_CONTENT
        );
       }




}

