<?php

namespace App\Controller\Api;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    /**
     * @Route("/api/artists", name="app_api_artist_add", methods={"POST"})
     * 
     * @OA\RequestBody(
     *     @Model(type=ArtistType::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="new created artist",
     *     @OA\JsonContent(
     *          ref=@Model(type=Artist::class, groups={"artist_read", "event_read", "category_read", "organizer_read"})
     *      )
     * )
     * 
     * @OA\Response(
     *     response=422,
     *     description="NotEncodableValueException"
     * )
     */

     //* Add an artist
     public function add(
        Request $request,
        SerializerInterface $serializer,
        ArtistRepository $artistRepository,
        ValidatorInterface $validator
        )
     {
        $contentJson = $request->getContent();

        try {
            $artistFromJson = $serializer->deserialize(
                $contentJson,
                Artist::class,
                'json'
            );
        } catch (\Throwable $e){
            return $this->json(
                $e->getMessage(),
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $listError = $validator->validate($artistFromJson);

        if (count($listError) > 0){
            // we have errors
            return $this->json(
                $listError,
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // persist + flush
        $artistRepository->add($artistFromJson, true);

        // inform user
        return $this->json(
            $artistFromJson,
            // code 201
            Response::HTTP_CREATED,
            [],
            [
                "groups" =>
                [
                    "artist_read",
                    "event_read",
                    "category_read",
                    "organizer_read"
                ]
            ]
                );
     }
}

