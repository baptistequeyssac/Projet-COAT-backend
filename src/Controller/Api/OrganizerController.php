<?php

namespace App\Controller\Api;

use App\Repository\OrganizerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

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































}

