<?php

namespace App\Controller\Api;

use App\Entity\Organizer;
use App\Repository\OrganizerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrganizerController extends AbstractController
{
    /**
     * @Route("/api/organizers", name="app_api_organizer", methods={"GET"})
     * 
     *  @OA\Response(
     *     response=200,
     *     description="Returns all the organizers",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Organizer::class, groups={"organizer_browse"}))
     *     )
     * )
     */

    //* Return all organizers
    public function browse(OrganizerRepository $organizerRepository): JsonResponse
    { 
        $allOrganizer = $organizerRepository->findAll();
        
        return $this->json(
            // object to be transmitted
            $allOrganizer,
            Response::HTTP_OK,
            [],
            [
                "groups" => 
              [
                 "organizer_browse"
              ] 
            ]
        );
    }

    /**
     * @Route("/api/organizers", name="app_api_organizer_add", methods={"POST"})
     * 
     * @OA\RequestBody(
     *     @Model(type=OrganizerType::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="new created organizer",
     *     @OA\JsonContent(
     *          ref=@Model(type=Organizer::class, groups={"organizer_read", "event_read , "artist_read"})
     *      )
     * )
     * 
     * @OA\Response(
     *     response=422,
     *     description="NotEncodableValueException"
     * )
     */

     //* Add an organizer
    public function add(
        Request $request,
        SerializerInterface $serializer, 
        OrganizerRepository $organizerRepository,
        ValidatorInterface $validator
        )

    {

    //* Collect information from the front
    $contentJson = $request->getContent();

    try {
        $organizerFromJson = $serializer->deserialize(
            $contentJson,
            Organizer::class,
            'json'
        );

     // We will only have NotEncodableValueExeption
    } catch (\Throwable $e){ 
        // Error message 
        return $this->json(
            $e->getMessage(),
            // code http : 422
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    // We validate the data before persit/flush
    $listError = $validator->validate($organizerFromJson);

    if (count($listError) > 0){
        // Error message 
        return $this->json(
            $e->getMessage(),
            // code http : 422
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    // persist + flush
    $organizerRepository->add($organizerFromJson, true);

    // Appropriate http code: 201 => Response ::HTTP_CREATED
    return $this->json(
        $organizerFromJson,
        // Change code http for 201
        Response::HTTP_CREATED,
        // No headers
        [],
        // For serialize, we use groups
        [
            "groups" => 
            [
                "organizer_read",
                "event_read"
                "artist_read"
            ]
        ]
    );
}

    /**
     * @Route("/api/organizer/{id}", name="app_api_organizer_edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
     */
    public function edit(
        Organizer $organizer = null, 
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
        )
    {
        // We modify an entity
        // Route parameter
        if ($organizer === null){
        // paramConverter didn't find entity: 404
        return $this->json("Organizer non trouvé", Response::HTTP_NOT_FOUND);
    }
    // Request information
    $jsonContent = $request->getContent();

    // deserialize
    try {
        $serializer->deserialize(
            $jsonContent,
            Organizer::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $organizer]
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
    $listError = $validator->validate($organizer);

    if (count($listError) >0) {
        return $this ->json(
            $listError,
            //code http : 422
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    // Here, $organizer has been modified
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
 *  @Route("/api/organizers/{id}", name="app_api_organizer_read, requirements={"id"="\d+"}, methods="GET"})
 */
public function read(Organizer $organizer = null)
{
    //if the user provided a wrong ID, I give a 404 error http
    if ($organizer === null) {
        return $this->json(
            [
                "message" => "cet organisateur n'existe pas"
            ],
            // error http 404
            Response::HTTP_NOT_FOUND
        );
    }

    return $this->json(
        $organizer,
        Response::HTTP_FOUND,
        [],
        [
            "groups" =>
            [
                "organizer_read",
                "event_read"
                "artist_read"
            ]
        ]
    )
}

/**
 * @Route("/api/genres/{id}", name="app_api_organizer_delete", requirements={"id"="\d+"}, methods={"DELETE"})
 */
public function delete(Organizer $organizer = null, OrganizerRepository $organizerRepository)
{
    // entity to delete: route parameter
    if ($organizer === null){
        // paramConverter didn't find entity : error http 404
        return $this->json("Organizer non trouvé" , Response::HTTP_NOT_FOUND); 
    }

    // No JSON, no validation of data
    // Delet
    $organizerRepository->remove($organizer, true);

    // Will still return a code
    return $this->json(
        null,
        Response::HTTP_NO_CONTENT
    );

}


/**
 * @Route("/api/organizers/{id}/events", name="app_api_events_by_organizer", requirements={"id"="\d+"}, methods={"GET})
 */
public function eventsByOrganizer()
{

}


/**
 * @Route("/api/organizerq/{id}/artists", name="app_api_artists_by_organizer", requirements={"id"="\d+"},methods={"GET})
 */
public function artistsByOrganizer()
{

}

}

