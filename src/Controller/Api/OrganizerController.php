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
     * @Route("/api/Organizer", name="app_api_Organizer", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all Organizers",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Organizer::class, groups={"Organizer_browse"}))
     *      )    
     * )
     */

    //* Return all Organizers
    public function browse(OrganizerRepository $OrganizerRepository): JsonResponse
    {
        $allOrganizer = $OrganizerRepository->findAll();

        return $this->json(
            $allOrganizer,
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    "Organizer_browse"
                ]
            ]
        );

    }

    /**
     * @Route("/api/Organizers", name="app_api_Organizer_add", methods={"POST"})
     * 
     * @OA\RequestBody(
     *     @Model(type=OrganizerType::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="new created Organizer",
     *     @OA\JsonContent(
     *          ref=@Model(type=Organizer::class, groups={"Organizer_read", "event_read", "category_read", "organizer_read"})
     *      )
     * )
     * 
     * @OA\Response(
     *     response=422,
     *     description="NotEncodableValueException"
     * )
     */

     //* Add an Organizer
     public function add(
        Request $request,
        SerializerInterface $serializer,
        OrganizerRepository $OrganizerRepository,
        ValidatorInterface $validator
        )
     {
        $contentJson = $request->getContent();

        try {
            $OrganizerFromJson = $serializer->deserialize(
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

        $listError = $validator->validate($OrganizerFromJson);

        if (count($listError) > 0){
            // we have errors
            return $this->json(
                $listError,
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // persist + flush
        $OrganizerRepository->add($OrganizerFromJson, true);

        // inform user
        return $this->json(
            $OrganizerFromJson,
            // code 201
            Response::HTTP_CREATED,
            [],
            [
                "groups" =>
                [
                    "Organizer_read",
                    "event_read",
                    "category_read",
                    "organizer_read"
                ]
            ]
                );
     }

     /**
      * @Route("/api/Organizers/{id}", name="app_api_Organizer_edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
      */

      //* Edit/update an Organizer
     public function edit(
        Organizer $Organizer = null,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
     )
     {
        if ($Organizer === null) {
            // paramConverter dont found the entity : code 404
            return $this->json("Organizere non trouvé", Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $request->getContent();

        try {
            $serializer->deserialize(
                $jsonContent,
                Organizer::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $Organizer]
            );
        } catch (\Throwable $e){
            return $this->json(
                $e->getMessage(),
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $listError = $validator->validate($Organizer);

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
      * @Route("/api/Organizers/{id}", name="app_api_Organizer_read", methods={"GET"}, requirements={"id"="\d+"})
      */

      //* Read an Organizer
      public function read(Organizer $Organizer = null)
      {
        // our user give a bad ID, We give a 404
        if ($Organizer === null){
            return $this->json(
                [
                    "message" => "Oups, il semblerait que cet Organizere n'existe pas"
                ],
                // code 404
                Response::HTTP_NOT_FOUND
            );
        }

         return $this->json(
            $Organizer,
            // code 302
            Response::HTTP_FOUND,
            [],
            [
                "groups" =>
                [
                    "Organizer_read",
                    "event_read",
                    "category_read",
                    "organizer_read"
                ]
            ]
        );
      }

      /**
       * @Route("/api/Organizers/{id}", name="app_api_Organizer_delete", methods={"DELETE"}, requirements={"id"="\d+"})
       */

       //* Delete an Organizer
       public function delete (Organizer $Organizer = null, OrganizerRepository $OrganizerRepository)
       {
        if ($Organizer === null){
            // paramConverter not found : code 404
            return $this->json("Organizere non trouvé", Response::HTTP_NOT_FOUND);
        }

        // delete
        $OrganizerRepository->remove($Organizer, true);

        return $this->json(
            null,
            // code 204
            Response::HTTP_NO_CONTENT
        );
       }




}

