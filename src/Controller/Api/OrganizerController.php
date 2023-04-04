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
     * @Route("/api/organizer", name="app_api_organizer", methods={"GET"})
     * 
     *  @OA\Response(
     *     response=200,
     *     description="Returns all the organizer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Organizer::class, groups={"organizer_browse"}))
     *     )
     * )
     */

    //* Return all organizer
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
                 "genre_browse"
              ] 
            ]
        );
    }
}

