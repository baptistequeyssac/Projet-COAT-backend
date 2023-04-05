<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class EventController extends AbstractController
{
    /**
     * @Route("/api/event", name="app_api_event", methods={"GET"})
     * 
     *  @OA\Response(
     *     response=200,
     *     description="Returns all the events",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Event::class, groups={"event_browse"}))
     *     )
     * )
     */

    //* Return all events
    public function browse(EventRepository $eventRepository): JsonResponse
    { 
        $allEvent = $eventRepository->findAll();
        
        return $this->json(
            // object to be transmitted
            $allEvent,
            Response::HTTP_OK,
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
     *          ref=@Model(type=Event::class, groups={"even_read", "event_read , "artist_read"})
     *      )
     * )
     * 
     * @OA\Response(
     *     response=422,
     *     description="NotEncodableValueException"
     * )
     */

     //* Add an event
//     public function add(
//         Request $request,
//         SerializerInterface $serializer, 
//         EventRepository $eventRepository,
//         ValidatorInterface $validator
//         )
//     {

//     //* Collect information from the front
//     $contentJson = $request->getContent();

//     try {
//         $eventFromJson = $serializer->deserialize(
//             $contentJson,
//             Event::class,
//             'json'
//         );

//      // We will only have NotEncodableValueExeption
//     } catch (\Throwable $e){ 
//         // Error message 
//         return $this->json(
//             $e->getMessage(),
//             // code http : 422
//             Response::HTTP_UNPROCESSABLE_ENTITY
//         );
//     }

//     // We validate the data before persit/flush
//     $listError = $validator->validate($eventFromJson);

//     if (count($listError) > 0){
//         // Error message 
//         return $this->json(
//             $listError,
//             // code http : 422
//             Response::HTTP_UNPROCESSABLE_ENTITY
//         );
//     }

//     // persist + flush
//     $eventRepository->add($eventFromJson, true);

//     // Appropriate http code: 201 => Response ::HTTP_CREATED
//     return $this->json(
//         $eventFromJson,
//         // Change code http for 201
//         Response::HTTP_CREATED,
//         // No headers
//         [],
//         // For serialize, we use groups
//         [
//             "groups" => 
//             [
//                 "organizer_read",
//                 "event_read",
//                 "artist_read"
//             ]
//         ]
//     );
// }

    /**
     * @Route("/api/events/{id}", name="app_api_event_edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
     */
    public function edit(
        Event $event = null, 
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
        )
    {
        // We modify an entity
        // Route parameter
        if ($event === null){
        // paramConverter didn't find entity: 404
        return $this->json("Event non trouvé", Response::HTTP_NOT_FOUND);
    }
    // Request information
    $jsonContent = $request->getContent();

    // deserialize
    try {
        $serializer->deserialize(
            $jsonContent,
            Event::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $event]
        );
    } catch (\Throwable $e){
        // Warn the utilisator
        return $this->json(
            $e->getMessage(),
            // code http : 422
            Response::HTTP_UNPROCESSABLE_ENTITY

        );
    }
    // We use a serializer option to update our entity

    // We validate data before persist/flush
    $listError = $validator->validate($event);

    if (count($listError) >0) {
        return $this ->json(
            $listError,
            //code http : 422
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    // Here, $event has been modified
    $entityManager->flush();

    // Return json
    return $this->json(
        // No data to return, it's an update
        null,
        //code http: 204
        Response::HTTP_NO_CONTENT
    );
}

/**
 *  @Route("/api/events/{id}", name="app_api_event_read, requirements={"id"="\d+"}, methods="GET"})
 */
// public function read(Event $event = null)
// {
//     //if the user provided a wrong ID, I give a 404 error http
//     if ($event === null) {
//         return $this->json(
//             [
//                 "message" => "cet événement n'existe pas"
//             ],
//             // error http 404
//             Response::HTTP_NOT_FOUND
//         );
//     }

//     return $this->json(
//         $event,
//         Response::HTTP_FOUND,
//         [],
//         [
//             "groups" =>
//             [
//                 "organizer_read",
//                 "event_read",
//                 "artist_read"
//             ]
//         ]
//             );
// }

/**
 * @Route("/api/events/{id}", name="app_api_event_delete", requirements={"id"="\d+"}, methods={"DELETE"})
 */
public function delete(Event $event = null, EventRepository $eventRepository)
{
    // entity to delete: route parameter
    if ($event === null){
        // paramConverter didn't find entity : error http 404
        return $this->json("Event non trouvé" , Response::HTTP_NOT_FOUND); 
    }

    // No JSON, no validation of data
    // Delet
    $eventRepository->remove($event, true);

    // Will still return a code
    return $this->json(
        null,
        Response::HTTP_NO_CONTENT
    );

}


}

