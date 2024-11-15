<?php

namespace App\Controller;

use App\Entity\Media;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\FileUploader;
use App\Service\MediaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Controlleur pour la gestion des medias

#[Route('/apis/media', name: 'app_media')]
class MediaController extends AbstractController
{
    // #[Route('/media', name: 'app_media')]
    // public function index(): JsonResponse
    // {
    //     return $this->json([
    //         'message' => 'Welcome to your new controller!',
    //         'path' => 'src/Controller/MediaController.php',
    //     ]);
    // }
    // Récupération de la liste des média
    // #[Route('', name: 'api_media_list', methods: ['GET'])]
    // public function list(MediaRepository $mediaRepository): JsonResponse
    // {
    //     $medias = $mediaRepository->findAll();

    //     $data = array_map(function ($media) {
    //         return [
    //             'id' => $media->getId(),
    //             'name' => $media->getName(),
    //             'url' => $media->getPath(),
    //             'type' => $media->getType(),
    //             'size' => $media->getSize(),
    //             'createdAt' => $media->getCreatedAt()->format('Y-m-d H:i:s'),
    //         ];
    //     }, $medias);

    //     return $this->json($data);
    // }
    // // Ajout de nouveaux media el chaîne
    // #[Route('', name: 'api_media_upload', methods: ['POST'])]
    // public function upload(Request $request, FileUploader $fileUploader,EntityManagerInterface $em): JsonResponse
    // {
    //     $files = $request->files->get('files');
    //     $uploadedMedias = [];
    //     // dd($files);
    //     if ($files) {
    //         foreach ($files as $file) {
    //             dd("foreach");

    //             if ($file->isValid()) {
    //                 // dd($files->isValid());
    //                 dd("isValid");
    //                 try {
    //                     $fileName = $fileUploader->upload($file);
    
    //                     $media = new Media();
    //                     $media->setName($file->getClientOriginalName());
    //                     $media->setPath('/uploads/' . $fileName);
    //                     $media->setType($file->getMimeType());
    //                     $media->setSize($file->getSize());    
    //                     $em->persist($media);
    //                     $em->flush();
    
    //                     $uploadedMedias[] = [
    //                         'id' => $media->getId(),
    //                         'name' => $media->getName(),
    //                         'url' => $media->getPath(),
    //                         'type' => $media->getType(),
    //                         'size' => $media->getSize(),
    //                         'createdAt' => $media->getCreatedAt()->format('Y-m-d H:i:s'),
    //                     ];
    //                 } catch (FileException $e) {
    //                     return $this->json(['error' => 'Failed to upload file'], 500);
    //                 }
    //             }
    //             else{
    //                 dd("else");
    //             }
    //         }
    //     }
    
    //     return $this->json(['medias' => $uploadedMedias]);
    // }
    // // Suppresion d'un media
    // #[Route('/remove/{id}', name: 'api_media_delete', methods: ['DELETE'])]
    // public function delete(int $id, MediaRepository $mediaRepository,EntityManagerInterface $em): JsonResponse
    // {
    //     $media = $mediaRepository->find($id);

    //     if (!$media) {
    //         return $this->json(['error' => 'Media not found'], 404);
    //     }

    //     // Supprimer le fichier du système
    //     $filePath = $this->getParameter('media_directory') . '/' . $media->getPath();
    //     if (file_exists($filePath)) {
    //         unlink($filePath);
    //     }

    //     // Supprimer l'entité
    //     $em->remove($media);
    //     $em->flush();

    //     return $this->json(['message' => 'Media deleted successfully']);
    // }

    public function __construct(private MediaService $mediaService)
    {
    }

    #[Route('', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $media = $this->mediaService->getAll($request->query->all());
        return $this->json($media);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function get(Media $media): JsonResponse
    {
        return $this->json($media);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $media = $this->mediaService->create($request->files->all());
        return $this->json($media, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, Media $media): JsonResponse
    {
        $this->mediaService->update($media, $request->request->all());
        return $this->json($media);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Media $media): JsonResponse
    {
        $this->mediaService->delete($media);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
