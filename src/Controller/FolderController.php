<?php

namespace App\Controller;

use App\Entity\Folder;
use App\Entity\FolderStatus;
use App\Entity\Level;
use App\Repository\FolderRepository;
use App\Repository\FolderStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FolderController extends AbstractController
{

    #[Route('/api/v1/level/{id}/folder/create', methods: [Request::METHOD_POST])]
    public function createFolder(Request $request, Level $level, EntityManagerInterface $entityManager, SluggerInterface $slugger): JsonResponse
    {
        $name = $request->request->get('name');

        $folder = (new Folder())->setName($name)->setLevel($level);

        $image = $request->files->get('image');

        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

            try {
                $image->move(
                    $this->getParameter('images_directory'), // Parameter defined in your services.yaml
                    $newFilename
                );
            } catch (FileException $e) {
                return new JsonResponse(['error' => 'Failed to upload image'], 500);
            }

            $imagePath = '/uploads/images/' . $newFilename;

            $folder->setImage($imagePath);
        }


        $entityManager->persist($folder);
        $entityManager->flush();

        return new JsonResponse();
    }


    #[Route('/api/v1/level/{id}/folder/list', methods: [Request::METHOD_GET])]
    public function folderList(FolderRepository $folderRepository, Level $level): JsonResponse
    {
        $data = [];
        $folders = $folderRepository->findBy(['level' => $level]);

        foreach ($folders as $folder) {
            $data[] = [
                'id'      => $folder->getId(),
                'name'    => $folder->getName(),
                'levelId' => $folder->getLevel()->getId(),
                'level' => $folder->getLevel()->getName(),
                'image' => $folder->getImage(),
            ];
        }

        return new JsonResponse(['data' => $data]);
    }

    #[Route('/api/v1/level/{id}/folder/user/list', methods: [Request::METHOD_GET])]
    public function folderUserList(FolderRepository $folderRepository, Level $level, FolderStatusRepository $folderStatusRepository): JsonResponse
    {
        $folders = $folderRepository->findBy(['level' => $level]);

        $foldersWithStatus = $folderStatusRepository->findBy(['user' => $this->getUser()]);

        $data = [];

        foreach ($folders as $folder) {
            $status = 'incomplete';
            foreach ($foldersWithStatus as $folderWithStatus) {
                if ($folderWithStatus->getFolder() === $folder) {
                    $status = $folderWithStatus->getStatus();
                    break;
                }
            }

            $data[] = [
                'id'      => $folder->getId(),
                'name'    => $folder->getName(),
                'image'   => $folder->getImage(),
                'levelId' => $folder->getLevel()->getId(),
                'level'   => $folder->getLevel()->getName(),
                'status'  => $status,
            ];

        }

        return new JsonResponse(['data' => $data]);
    }

    #[Route('api/v1/folder/{id}/delete', methods: [Request::METHOD_DELETE])]
    public function deleteFolder(EntityManagerInterface $entityManager, Folder $folder): JsonResponse
    {
        if (!$folder->getCards()->isEmpty()) {
            throw new HttpException(statusCode: 400);
        }

        $entityManager->remove($folder);
        $entityManager->flush();

        return new JsonResponse();
    }

}