<?php

namespace App\Controller\Api;

use App\Entity\Type;
use App\Repository\TypeRepository;
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

class TypeController extends AbstractController
{
    /**
     * @Route("/api/types", name="app_api_type", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all types",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Type::class, groups={"type_browse"}))
     *      )
     * )
     */

    //* Return all types
    public function browse(TypeRepository $typeRepository): JsonResponse
    {
        $allType = $typeRepository->findAll();

        return $this->json(
            $allType,
            // code 200
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    "type_browse"
                ]
            ]

        );
    }
}