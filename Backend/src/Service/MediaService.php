<?php 
namespace App\Service;

use App\Entity\Media;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaService
{
    public function __construct(
        private MediaRepository $mediaRepository,
        private EntityManagerInterface $entityManager,
        private string $mediaDirectory
    ) {
    }

    public function getAll(array $filters = []): array
    {
        return $this->mediaRepository->findBy(['isDeleted' => false], $filters);
    }

    public function create(array $files): Media
    {
        $media = new Media();
        foreach ($files as $file) {
            $this->processFile($media, $file);
        }
        $this->entityManager->persist($media);
        $this->entityManager->flush();
        return $media;
    }

    public function update(Media $media, array $data): void
    {
        $media->setName($data['name'] ?? $media->getName());
        $this->entityManager->flush();
    }

    public function delete(Media $media): void
    {
        $media->setDeleted(true);
        $this->entityManager->flush();
    }

    private function processFile(Media $media, UploadedFile $file): void
    {
        // $fileName = uniqid('media_') . '.' . $file->guessExtension();
        // $filePath = $this->mediaDirectory . '/' . $fileName;
        // $file->move($this->mediaDirectory, $fileName);

        // $media->setName($file->getClientOriginalName());
        // $media->setPath($filePath);
        // $media->setType($file->getMimeType());
        // $media->setSize($file->getSize());
        $fileName = uniqid('media_') . '.' . $file->guessExtension();
        $filePath = sys_get_temp_dir() . '/' . $fileName;
        $file->move(sys_get_temp_dir(), $fileName);
    
        // Déplacez ensuite le fichier vers le répertoire de stockage final
        $finalPath = $this->mediaDirectory . '/' . $fileName;
        rename($filePath, $finalPath);
    
        $media->setName($file->getClientOriginalName());
        $media->setPath($finalPath);
        $media->setType($file->getMimeType());
        $media->setSize($file->getSize());
    }
}