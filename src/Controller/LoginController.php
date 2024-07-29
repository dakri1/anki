<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{

    #[Route('api/auth/login', methods: [Request::METHOD_POST])]
    public function login(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): JsonResponse
    {
        $data = $request->toArray();
        $email = $data['email'];
        $password = $data['password'];

        $user = $userRepository->findOneBy(['email' => $email]);
        if (!$user instanceof User) {
            throw new HttpException(statusCode: Response::HTTP_BAD_REQUEST);
        }

        if (!$userPasswordHasher->isPasswordValid($user, $password)) {
            throw new HttpException(statusCode: Response::HTTP_UNAUTHORIZED);

        }

        return new JsonResponse(['token' => $user->getToken()]);
    }

    #[Route('api/auth/registration', methods: [Request::METHOD_POST])]
    public function registration(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $data = $request->toArray();
        $email = $data['email'];
        $password = $data['password'];
        $token = sha1(time());
        $user = (new User())->setEmail($email);

        $hashedPassword = $userPasswordHasher->hashPassword(
            $user,
            $password,
        );

        $user->setPassword($hashedPassword)->setToken($token);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['status' => "Registration successfully"]);
    }

}