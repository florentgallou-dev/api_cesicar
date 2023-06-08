<?php

namespace App\Controller\Admin;

use App\Entity\Travel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TravelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Travel::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Voyage')
            ->setEntityLabelInPlural('Voyages')
            ->setSearchFields(['name', 'user'])
            ->setDefaultSort(['created_at' => 'DESC'])
            ->setEntityPermission('ROLE_EDITOR');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        
        yield AssociationField::new('user', 'Conducteur');

        yield TextField::new('name', 'Nom du voyage');
        yield BooleanField::new('to_cesi', 'Voyage en direction de CESI')->renderAsSwitch(true);
        yield ArrayField::new('position', 'Position')
                ->onlyOnForms();
        yield TextField::new('adress', 'Adresse')
                ->onlyOnForms();
        yield NumberField::new('zip_code', 'Code postal')
                ->onlyOnForms();
        yield TextField::new('city', 'Ville');
        yield DateField::new('departure_date', 'Date de départ');
        yield NumberField::new('number_seats', 'Places disponibles');

        yield AssociationField::new('voyagers');
        yield AssociationField::new('user');
    }
}
