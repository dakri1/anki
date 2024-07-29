<?php

namespace App\Controller;

use App\Entity\Folder;
use App\Entity\FolderStatus;
use App\Repository\FolderStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class FolderStatusController extends AbstractController
{

    #[Route('/api/v1/folder/{id}/status', methods: [Request::METHOD_POST])]
    public function setFolderStatus(Folder $folder, Request $request, FolderStatusRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();

        $user = $this->getUser();

        $folderStatus = $repository->findOneBy(['user' => $user, 'folder' => $folder]);

        if (!$folderStatus instanceof FolderStatus) {
            $folderStatus = (new FolderStatus())->setFolder($folder)->setUser($user);
        }

        $folderStatus->setStatus($data['status']);

        $entityManager->persist($folderStatus);
        $entityManager->flush();

        return new JsonResponse();
    }
    
}