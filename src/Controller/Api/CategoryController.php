<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
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

class CategoryController extends AbstractController
{
    /**
     * @Route("/api/categories", name="app_api_category", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all categories",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Category::class, groups={"category_browse"}))
     *      )
     * )
     */

    //* Return all categories
    public function browse(CategoryRepository $categoryRepository): JsonResponse
    {
        $allCategory = $categoryRepository->findAll();

        return $this->json(
            $allCategory,
            // code 200
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    "category_browse"
                ]
            ]

        );
    }
}

