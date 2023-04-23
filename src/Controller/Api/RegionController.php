<?php

namespace App\Controller\Api;

use App\Entity\Region;
use App\Repository\RegionRepository;
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

class RegionController extends AbstractController
{
    /**
     * @Route("/api/regions", name="app_api_region", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all regions",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Region::class, groups={"region_browse"}))
     *      )
     * )
     */

    //* Return all regions
    public function browse(RegionRepository $regionRepository): JsonResponse
    {
        $allRegion = $regionRepository->findAll();

        return $this->json(
            $allRegion,
            // code 200
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    "region_browse"
                ]
            ]

        );
    }
}
