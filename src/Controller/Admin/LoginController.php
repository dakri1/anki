<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Errors\AuthenticationError;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/login', 'login', methods: [Request::METHOD_POST, Request::METHOD_GET])]
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, Security $security): Response
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            $email = $request->request->get('_username');
            $plainPassword = $request->request->get('_password');

            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user instanceof User && $userPasswordHasher->isPasswordValid($user, $plainPassword)) {
                $security->login($user);

                return $this->redirectToRoute('admin');
            }

            $error = new AuthenticationError('Email or Password is incorrect', [
                '%email%' => $email, // Пример, как можно использовать данные
            ]);
        }

        // Передаем переменные в шаблон логина
        return $this->render('admin/login.html.twig', [
            'action' => $this->generateUrl('login'),
            'error' => $error ?? '',
            'page_title' => 'Anki Admin Area', // Заголовок
//            'favicon_path' => $this->generateUrl('path_to_your_favicon'), // Путь к фавикону
//            'logo_path' => $this->generateUrl(''), // Путь к логотипу
            'username_label' => 'Email',
            'csrf_token_intention' => 'authenticate' // Имя токена для CSRF
        ]);
    }

    #[Route('/logout', 'logout')]
    public function logout(Security $security): Response
    {
        $security->logout();

        return $this->redirectToRoute('login');
    }
}