<?php

namespace App\Controller\Api;

use App\Entity\Organizer;
use App\Repository\OrganizerRepository;
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



class OrganizerController extends AbstractController
{
    /**
     * @Route("/api/organizers", name="app_api_organizer", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all organizers",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Organizer::class, groups={"organizer_browse"}))
     *      )    
     * )
     */

    //* Return all organizers
    public function browse(OrganizerRepository $organizerRepository): JsonResponse
    {
        $allOrganizer = $organizerRepository->findAll();

        return $this->json(
            $allOrganizer,
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    
                    "organizer_browse",
                    
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
     *          ref=@Model(type=Organizer::class, groups={"organizer_add"})
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
        $contentJson = $request->getContent();

        try {
            $organizerFromJson = $serializer->deserialize(
                $contentJson,
                Organizer::class,
                'json'
            );
        } catch (\Throwable $e){
            return $this->json(
                $e->getMessage(),
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $listError = $validator->validate($organizerFromJson);

        if (count($listError) > 0){
            // we have errors
            return $this->json(
                $listError,
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // persist + flush
        $organizerRepository->add($organizerFromJson, true);

        // inform user
        return $this->json(
            $organizerFromJson,
            // code 201
            Response::HTTP_CREATED,
            [],
            [
                "groups" =>
                [
                    "organizer_add"                   
                ]
            ]
                );
     }

     /**
      * @Route("/api/organizers/{id}", name="app_api_organizer_edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
      */

      //* Edit/update an organizer
     public function edit(
        Organizer $organizer = null,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
     )
     {
        if ($organizer === null) {
            // paramConverter dont found the entity : code 404
            return $this->json("Organisateur non trouvé", Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $request->getContent();

        try {
            $serializer->deserialize(
                $jsonContent,
                Organizer::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $organizer]
            );
        } catch (\Throwable $e){
            return $this->json(
                $e->getMessage(),
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $listError = $validator->validate($organizer);

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
      * @Route("/api/organizers/{id}", name="app_api_organizer_read", methods={"GET"}, requirements={"id"="\d+"})
      */

      //* Read an organizer
      public function read(Organizer $organizer = null)
      {
        // our user give a bad ID, We give a 404
        if ($organizer === null){
            return $this->json(
                [
                    "message" => "Oups, il semblerait que cet organisateur n'existe pas"
                ],
                // code 404
                Response::HTTP_NOT_FOUND
            );
        }

         return $this->json(
            $organizer,
            // code 200
            Response::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "organizer_read",   
                ]
            ]
        );
      }

      /**
       * @Route("/api/organizers/{id}", name="app_api_organizer_delete", methods={"DELETE"}, requirements={"id"="\d+"})
       */

       //* Delete an organizer
       public function delete (Organizer $organizer = null, OrganizerRepository $organizerRepository)
       {
        if ($organizer === null){
            // paramConverter not found : code 404
            return $this->json("Organisateur non trouvé", Response::HTTP_NOT_FOUND);
        }

        // delete
        $organizerRepository->remove($organizer, true);

        return $this->json(
            null,
            // code 204
            Response::HTTP_NO_CONTENT
        );
       }




}

