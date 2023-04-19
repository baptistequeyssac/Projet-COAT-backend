<?php

namespace App\Controller\Api;

use App\Entity\Artist;
use App\Entity\User;
use App\Repository\ArtistRepository;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
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
use Symfony\Component\Security\Core\Security;

class ArtistController extends AbstractController
{
    /**
     * @Route("/api/artists", name="app_api_artist", methods={"GET"})
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
     *          ref=@Model(type=Artist::class, groups={"artist_add"})
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
        ValidatorInterface $validator,
        UserRepository $userRepository,
        Security $security
        
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
        
        // // ! TEST ! \\
        // // get user from artist
        // $user = $this->getUser();
        // if (!$user instanceof User) {
        //     return $this->json(
        //         'Utilisateur non trouvé',
        //         //code 404
        //         Response::HTTP_NOT_FOUND
        //     );
        // }

        // // set user for this artist
        // $user->setArtist($artistFromJson);
        // // ! TEST ! \\

        // persist + flush
        $artistRepository->add($artistFromJson, true);

        // Associate artist with logged user
        $user = $security->getUser();
        if ($user instanceof User){
            $artistFromJson->setUser($user);
            $artistRepository->save($artistFromJson);
        }

        // inform user
        return $this->json(
            $artistFromJson,
            // code 201
            Response::HTTP_CREATED,
            [],
            [
                "groups" =>
                [
                    "artist_add",
                ]
            ]
                );
     }

     /**
      * @Route("/api/artists/{id}", name="app_api_artist_edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
      */

      //* Edit/update an artist
     public function edit(
        Artist $artist = null,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
     )
     {
        if ($artist === null) {
            // paramConverter dont found the entity : code 404
            return $this->json("Artiste non trouvé",
            // code 404 
            Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $request->getContent();

        try {
            $serializer->deserialize(
                $jsonContent,
                Artist::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $artist]
            );
        } catch (\Throwable $e){
            return $this->json(
                $e->getMessage(),
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $listError = $validator->validate($artist);

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
      * @Route("/api/artists/{id}", name="app_api_artist_read", methods={"GET"}, requirements={"id"="\d+"})
      */

      //* Read an artist
      public function read(Artist $artist = null)
      {
        // our user give a bad ID, We give a 404
        if ($artist === null){
            return $this->json(
                [
                    "message" => "Oups, il semblerait que cet artiste n'existe pas"
                ],
                // code 404
                Response::HTTP_NOT_FOUND
            );
        }

         return $this->json(
            $artist,
            // code 200
            Response::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "artist_read",
                ]
            ]
        );
      }

      /**
       * @Route("/api/artists/{id}", name="app_api_artist_delete", methods={"DELETE"}, requirements={"id"="\d+"})
       */

       //* Delete an artist
       public function delete (Artist $artist = null, ArtistRepository $artistRepository)
       {
        if ($artist === null){
            // paramConverter not found : code 404
            return $this->json("Artiste non trouvé", Response::HTTP_NOT_FOUND);
        }

        // delete
        $artistRepository->remove($artist, true);

        return $this->json(
            null,
            // code 204
            Response::HTTP_NO_CONTENT
        );
       }
}

