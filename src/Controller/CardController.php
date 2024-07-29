<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Folder;
use App\Entity\Language;
use App\Entity\Level;
use App\Repository\CardRepository;
use App\Repository\LanguageRepository;
use App\Repository\LevelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CardController extends AbstractController
{

    #[Route('/api/v1/folder/{id}/card/create', methods: [Request::METHOD_POST])]
    public function createCard(
        Folder                 $folder,
        EntityManagerInterface $entityManager,
        Request                $request,
    ): JsonResponse
    {
        $data = $request->toArray();

        $word = $data['word'];
        $translation = $data['translation'];
        $sentence = $data['sentence'];

        $card = (new Card())->setWord($word)->setTranslation($translation)->setSentence($sentence)->setFolder($folder);

        $entityManager->persist($card);
        $entityManager->flush();

        return new JsonResponse();

    }

    #[Route('/api/v1/folder/{id}/card/list', methods: [Request::METHOD_GET])]
    public function cardList(CardRepository $cardRepository, Folder $folder): JsonResponse
    {
        $data = [];
        $cards = $cardRepository->findBy(['folder' => $folder]);

        foreach ($cards as $card) {
            $data[] = [
                'id' => $card->getId(),
                'word' => $card->getWord(),
                'translation' => $card->getTranslation(),
                'sentence' => $card->getSentence(),
                'folderId' => $card->getFolder()->getId(),
            ];
        }

        return new JsonResponse(['data' => $data]);
    }

    #[Route('/api/v1/card/{id}/delete', methods: [Request::METHOD_DELETE])]
    public function deleteCard(Card $card, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($card);
        $entityManager->flush();

        return new JsonResponse();
    }

}