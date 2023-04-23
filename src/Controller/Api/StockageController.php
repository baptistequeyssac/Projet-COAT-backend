<?php

namespace App\Controller\Api;

use App\Entity\Stockage;
use App\Repository\EventRepository;
use App\Repository\StockageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class StockageController extends AbstractController
{
    /**
     * @Route("/api/stockage", name="app_api_stockage")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            
        ]);
    }

    /**
     * @Route("/api/upload_image", name="app_api_upload_image", methods={"POST"})
     */

    //* Upload an image in stockage 
    public function uploadImage(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $image = new Stockage();

        $file = $request->files->get('image');
        $fileUserId = $request->request->get('id');
        //dd($fileUserId);
        
        $fileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                'images',
                $fileName
            );
        } catch (FileException $e) {
            return $this->json(
                'Oups, il y a un soucis avec cette image',
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // Take user ...
        $user = $userRepository->find($fileUserId);
        // dd($user);
        if (!$user) {
            return $this->json(
                'Utilisateur non trouvé',
                // code 404
                Response::HTTP_NOT_FOUND
            );
        }
        
        //dd($image);
        
        $image->setImage($fileName);
        $image->setUser($user);
        // dd($image);
        // persit + flush
        $entityManager->persist($image);
        $entityManager->flush();
        
        return $this->json(
            'Image importé avec succès',
            // code 200
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/api/upload_image_event", name="app_api_upload_image_event", methods={"POST"})
     */

    //* Upload an event's image in stockage 
    public function uploadImageEvent(Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository)
    {
        $image = new Stockage();

        $file = $request->files->get('image');
        $fileEventId = $request->request->get('id');
        //dd($fileEventId);
        
        $fileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                'images',
                $fileName
            );
        } catch (FileException $e) {
            return $this->json(
                'Oups, il y a un soucis avec cette image',
                // code 422
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // Take event ...
        $event = $eventRepository->find($fileEventId);
        // dd($event);
        if (!$event) {
            return $this->json(
                'Utilisateur non trouvé',
                // code 404
                Response::HTTP_NOT_FOUND
            );
        }
        
        //dd($image);
        
        $image->setImage($fileName);
        $image->setEvent($event);
        // dd($image);
        // persit + flush
        $entityManager->persist($image);
        $entityManager->flush();
        
        return $this->json(
            'Image importé avec succès',
            // code 200
            Response::HTTP_OK
        );
    }

    /**
     *  @Route("/api/stockage/image/{id}", name="app_api_image_read", methods={"GET"})
     */

     //* Get/read an image
     public function read(Stockage $stockage)
     {
        if (!$stockage) {
            return $this->json(
                'Cette image n\'existe pas',
                // code 404
                Response::HTTP_NOT_FOUND
            );
        }
       // dd($stockage);
        return $this->json(
            $stockage,
            // 200
            Response::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "stockage_read"
                ]
            ]
            
        );
       // dd($stockage);
     }

     /**
     * @Route("/api/stockage", name="app_api_stockage", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns all stockage",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Artist::class, groups={"stockage_browse"}))
     *      )    
     * )
     */

    //* Return all stockage
    public function browse(StockageRepository $stockageRepository): JsonResponse
    {
        $allStockage = $stockageRepository->findAll();

        return $this->json(
            $allStockage,
            Response::HTTP_OK,
            // Third's parameter is empty because we need to access fourth parameter
            [],
            [
                "groups" =>
                [
                    "stockage_browse"
                ]
            ]
        );

    }

 }
