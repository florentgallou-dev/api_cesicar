<?php

namespace App\Controller\Admin;

use App\Entity\Report;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Report::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Rapport')
            ->setEntityLabelInPlural('Rapports')
            ->setSearchFields(['user', 'type_reportable'])
            ->setDefaultSort(['created_at' => 'DESC'])
            ->setEntityPermission('ROLE_EDITOR');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        
        yield AssociationField::new('user', 'Rapporteur');
        
        yield NumberField::new('id_reportable', 'Id du contenu');
        yield ChoiceField::new('type_reportable', 'Type de contenu')->setChoices([
            'Utilisateur' => 'App\Entity\User::class',
            'Voyage' => 'App\Entity\Travel::class',
            'Conversation' => 'App\Entity\Conversation::class',
            'Message' => 'App\Entity\Message::class',
        ]);
        yield TextField::new('message', 'Rapport');

    }
}
