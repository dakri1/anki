<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\Level;
use App\Repository\LanguageRepository;
use App\Repository\LevelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LevelController extends AbstractController
{

    #[Route('api/v1/language/{id}/level/create', methods: [Request::METHOD_POST])]
    public function createLevel(Request $request, EntityManagerInterface $entityManager, Language $language): JsonResponse
    {
        $data = $request->toArray();
        $name = $data['name'];

        $level = (new Level())->setName($name)->setLanguage($language);

        $entityManager->persist($level);
        $entityManager->flush();

        return new JsonResponse();
    }

    #[Route('api/v1/language/{id}/level/list', methods: [Request::METHOD_GET])]
    public function levelListByLanguage(LevelRepository $levelRepository, Language $language): JsonResponse
    {
        $data = [];
        $levels = $levelRepository->findBy(['language' => $language]);

        foreach ($levels as $level) {
            $data[] = [
                'id'   => $level->getId(),
                'name' => $level->getName(),
            ];
        }

        return new JsonResponse(['data' => $data]);

    }

    #[Route('/api/v1/level/{id}', methods: [Request::METHOD_DELETE])]
    public function deleteLevel(Level $level, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($level);
        $entityManager->flush();

        return new JsonResponse();

    }

}