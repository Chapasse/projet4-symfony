<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use phpDocumentor\Reflection\Types\Integer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AvisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Avis::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('email'),
            TextareaField::new('content')->hideOnForm(),
            TextEditorField::new('content')->onlyOnForms(),
            ChoiceField::new('category')->setChoices(['Hôtel' => 'Hôtel','Chambre' => 'Chambre', "Restaurant" => "Restaurant", 'Spa' => 'Spa',])->onlyOnForms(),
            IntegerField::new('note'),
            DateTimeField::new('date_enregistrement', "Date de paiement")->setFormat('d/M/Y à H:m:s')->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function createEntity(string $entityFqcn)
    {
        $pr = new $entityFqcn;
        $pr->setDateEnregistrement(new \DateTime);
        return $pr;
    }

}
