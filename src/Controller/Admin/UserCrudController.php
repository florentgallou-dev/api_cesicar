<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setSearchFields(['first_name', 'last_name', 'email'])
            ->setDefaultSort(['created_at' => 'DESC'])
            // ->setEntityPermission('ROLE_ADMINISTRATOR')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('first_name', 'Prénom');
        yield TextField::new('last_name', 'Nom');

        yield ChoiceField::new('gender', 'Genre')->setChoices(fn () => [
                                                        'Femme' => 'femme',
                                                        'Homme' => 'homme',
                                                        'Autre' => 'autre',
                                                    ]);
        
        yield EmailField::new('email', 'Email');
        yield TextField::new('password', 'Mot de passe')
                            ->hideOnIndex();

        yield TextField::new('city', 'Ville');

        yield BooleanField::new('driver', 'Conducteur')->renderAsSwitch(true);
        yield TextField::new('car_type', 'Modèle')
                            ->hideOnIndex();
        yield TextField::new('car_registration', 'Immatriculation')
                            ->hideOnIndex();
        yield IntegerField::new('car_nb_places', 'Nombre de places')
                            ->hideOnIndex();

        // yield AssociationField::new('inscription');
        // yield AssociationField::new('travel');
        // yield AssociationField::new('conversation');
        // yield AssociationField::new('message');
        // yield AssociationField::new('report');

    }

}
