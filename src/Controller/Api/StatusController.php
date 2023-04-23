<?php

namespace App\Controller\Api;

use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class StatusController extends AbstractController
{
    /**
     * @Route("/api/status", name="app_api_status", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all status",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Status::class, groups={"region_status"}))
     *      )
     * )
     */

    //* Return all regions
    public function browse(StatusRepository $statusRepository): JsonResponse
    {
        $allStatus = $statusRepository->findAll();

        return $this->json(
            $allStatus,
            // code 200
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    "status_browse"
                ]
            ]

        );
    }
}
