<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    /**
     * @var JWTTokenManagerInterface
     */
    private $jwtManager;
    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    /**
     * @Route("/api/users", name="app_api_user", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all user",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=User::class, groups={"user_browse"}))
     *      )    
     * )
     */

    //* Return all users
    public function browse(UserRepository $userRepository): JsonResponse
    {
        $allUser = $userRepository->findAll();
        return $this->json(
            $allUser,
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    "user_browse"
                ]
            ]
        );
    }

    /**
     * @Route("/api/users", name="app_api_user_add", methods={"POST"})
     * 
     * @OA\RequestBody(
     *     @Model(type=UserType::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="new created user",
     *     @OA\JsonContent(
     *          ref=@Model(type=User::class, groups={"user_read", "artist_read", "organizer_read"})
     *      )
     * )
     * 
     * @OA\Response(
     *     response=422,
     *     description="NotEncodableValueException"
     * )
     */

     //* Add an user
     public function add(
        Request $request,
        SerializerInterface $serializer,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $userPasswordHasherInterface
        )
    {
        $contentJson = $request->getContent();

        try {
            $userFromJson = $serializer->deserialize(
                $contentJson,
                User::class,
                'json'
            );
        } catch (\Throwable $e){
            return $this->json(
                $e->getMessage(),
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $listError = $validator->validate($userFromJson);

        if (count($listError) > 0){
            // we have errors
            return $this->json(
                $listError,
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // hash password
        $password = $userFromJson->getPassword();
        $hashedPassword = $userPasswordHasherInterface->hashPassword($userFromJson, $password);
        // assign hashed password to user
        $userFromJson->setPassword($hashedPassword);

        // persist + flush
        $userRepository->add($userFromJson, true);

        // inform user
        return $this->json(
            $userFromJson,
            // code 201
            Response::HTTP_CREATED,
            [],
            [
                "groups" =>
                [
                    "user_browse",
                    "user_read",
                    "artist_read",
                    "organizer_read"
                    
                ]
            ]
        );
    }

    /**
     * @Route("/api/login_check", name="app_api_user", methods={"POST"})
     */
   
     public function login(){
        // no content, use JWT function login
     }

     /**
      * @Route("/api/user/{id}", name="app_api_user_read", methods={"GET"}, requirements={"id"="\d+"})
      */

      //* Read an artist
      public function read(User $user = null)
      {
        // our user give a bad ID, We give a 404
        if ($user === null){
            return $this->json(
                [
                    "message" => "Oups, il semblerait que cet utilisateur n'existe pas"
                ],
                // code 404
                Response::HTTP_NOT_FOUND
            );
        }

         return $this->json(
            $user,
            // code 200
            Response::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "user_read",
                ]
            ]
        );
      }
}
