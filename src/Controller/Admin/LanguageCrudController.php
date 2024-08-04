<?php

namespace App\Controller\Admin;

use App\Entity\Language;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LanguageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Language::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Языки')  // Измените множественное название
            ->setEntityLabelInSingular('Язык'); // Измените единственное название
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Название'),
            BooleanField::new('isPublished', 'Опубликованно')->setRequired(false), // Включаем поле isPublished
        ];
    }
}
