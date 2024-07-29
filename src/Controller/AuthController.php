<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{

    #[Route('/api/register', methods: [Request::METHOD_POST])]
    public function register(Request $request): JsonResponse
    {
        $data = $request->toArray();
        return new JsonResponse(['data' => $data]);
    }

}