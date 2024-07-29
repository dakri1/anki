<?php

namespace App\Controller;

use App\Entity\Language;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class LanguageController extends AbstractController
{
    #[Route('api/v1/language/create', methods: [Request::METHOD_POST])]
    public function createLanguage(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        $language = (new Language())->setName($data['name']);

        $entityManager->persist($language);
        $entityManager->flush();

        return new JsonResponse();
    }

    #[Route('/api/v1/language/list', methods: [Request::METHOD_GET])]
    public function languageList(LanguageRepository $languageRepository): JsonResponse
    {
        $languages = $languageRepository->findAll();

        $data = [];

        foreach ($languages as $language) {
            $data[] = [
                'id'   => $language->getId(),
                'name' => $language->getName(),
            ];
        }

        return new JsonResponse(['data' => $data]);
    }

    #[Route('/api/v1/language/{id}', methods: [Request::METHOD_DELETE])]
    public function deleteLanguage(Language $language, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$language->getLevels()->isEmpty()) {
            return new JsonResponse(['error' => 'Language has levels, first, delete them'], status: Response::HTTP_BAD_REQUEST);
        }

        $entityManager->remove($language);
        $entityManager->flush();

        return new JsonResponse();
    }

}