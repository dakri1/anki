<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\UserCardStatus;
use App\Repository\UserCardStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserCardStatusController extends AbstractController
{

    #[Route('api/v1/card/{id}/status', methods: [Request::METHOD_POST])]
    public function createStatus(Request $request, EntityManagerInterface $entityManager, Card $card): JsonResponse
    {
        $data = $request->toArray();

        $status = $data['status'];

        $cardStatus = (new UserCardStatus())->setUser($this->getUser())->setCard($card)->setStatus($status);

        $entityManager->persist($cardStatus);
        $entityManager->flush();

        return new JsonResponse();
    }


    #[Route('api/v1/card/get/status', methods: [Request::METHOD_GET])]
    public function getInfoByUser(UserCardStatusRepository $repository): JsonResponse
    {

        $cards = $repository->findBy(['user' => $this->getUser()]);

        $data = [];

        foreach ($cards as $card) {
            $data[] = [
                'id' => $card->getId(),
                'username' => $card->getUser()->getEmail(),
                'card'    => $card->getCard()->getWord(),
                'status'    => $card->getStatus(),
            ];
        }

        return new JsonResponse(['data' => $data]);
    }
    
}