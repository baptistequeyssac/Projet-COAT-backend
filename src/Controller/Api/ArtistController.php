<?php

namespace App\Controller\Api;

use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;


class ArtistController extends AbstractController
{
    /**
     * @Route("/api/artist", name="app_api_artist", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all artists",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Artist::class, groups={"artist_browse"}))
     *      )    
     * )
     */

    //* Return all artists
    public function browse(ArtistRepository $artistRepository): JsonResponse
    {
        $allArtist = $artistRepository->findAll();

        return $this->json(
            $allArtist,
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    "artist_browse"
                ]
            ]
        );

    }
}

