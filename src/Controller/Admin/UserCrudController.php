<?php

// src/Controller/Admin/UserCrudController.php
namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordEncoder)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Пользователи')
            ->setPageTitle('new', 'Создание пользователя');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Другие поля
            IdField::new('id')->hideOnForm(),
            TextField::new('email'),
            TextField::new('password')
                ->setFormTypeOption('mapped', true)
//                ->setFormTypeOption('type', PasswordType::class) // Убедитесь, что используется поле для пароля
                ->setRequired(true),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        // Хэширование пароля
        $plainPassword = $entityInstance->getPassword();
        $hashedPassword = $this->passwordEncoder->hashPassword($entityInstance, $plainPassword);
        $entityInstance->setPassword($hashedPassword);

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        // Обновляйте пароль только если он был изменен
        if ($entityInstance->getPassword() !== null) {
            $plainPassword = $entityInstance->getPassword();
            $hashedPassword = $this->passwordEncoder->hashPassword($entityInstance, $plainPassword);
            $entityInstance->setPassword($hashedPassword);
        }

        $entityManager->flush();
    }
}
