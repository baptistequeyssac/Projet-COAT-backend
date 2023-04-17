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
     * @Route("/api/user/login", name="app_api_user_login", methods={"POST"})
     * 
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="password", type="string")
     *     )
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="authentication success",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="message", type="string", example="Authentication success"),
     *         @OA\Property(property="token", type="string")
     *     )
     * )
     * 
     * @OA\Response(
     *     response=401,
     *     description="authentication failure",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="message", type="string", example="Invalid username or password")
     *     )
     * )
     */

    //* Log an user
    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasherInterface
        ): JsonResponse
    {
        $contentJson = $request->getContent();
        $userData = json_decode($contentJson, true);

        

        // ! delete or not ?
        $email = $userData['email'] ?? null;
        $password = $userData['password'] ?? null;

        

         //! add comment

        $user = $userRepository->findOneBy(array('email' => $email, 'password' => $password));

        if (isset($user)){
            // generate token 
            $token = $this->jwtManager->create($user);

            return $this->json(
                [
                    'message' => 'Vous êtes connecté',
                    'token' => $token
                ],
                // code 200
                Response::HTTP_OK
            );
        } else {
            return $this->json(
                ['message' => "Oups, email ou mot de passe incorrect"],
                // code 401
                Response::HTTP_UNAUTHORIZED
                );
            }
    }
}
