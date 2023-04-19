<?php

namespace App\Controller\Api;

use App\Entity\Stockage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
    public function uploadImage(Request $request, EntityManagerInterface $entityManager){
        $image = new Stockage();

        $file = $request->files->get('image');
        $fileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                'images',
                $fileName
            );
        } catch (FileException $e) {
            return $this->json(
                ['message' => 'Oups, il y a un soucis avec cette image']
                // ajouter code http + response
            );
        }

        $image->setImage($fileName);

        // persit + flush
        $entityManager->persist($image);
        $entityManager->flush();

        return $this->json(
            ['message' => 'Image importé avec succès']
            // ajouter code http + response
        );
    }

    
}
