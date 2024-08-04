<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Card::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('word', 'Слово'),
            TextField::new('translation', 'Перевод'),
            TextField::new('sentence', 'Пример'),
            AssociationField::new('folder', 'Колода'),
            ImageField::new('image', 'Изображение')
                ->setBasePath('/uploads/images') // Путь до изображений
                ->setUploadDir('public/uploads/images') // Директория загрузки
                ->setRequired(false), // Необязательно
            BooleanField::new('isPublished', 'Опубликованно')->setRequired(false), // Включаем поле isPublished

        ];
    }
}
