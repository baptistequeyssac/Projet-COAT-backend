<?php

namespace App\Controller\Api;

use App\Entity\Stockage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

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
    public function uploadImage(Request $request, EntityManagerInterface $entityManager)
    {
        $image = new Stockage();

        $file = $request->files->get('image');
        //dd($file);
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

        $image->setImage($fileName);

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
        return $this->json(
            ['image' => $stockage->getImage()]
        );
     }
 }
