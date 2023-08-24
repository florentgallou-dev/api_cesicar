<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\{FormBuilderInterface, FormEvents};
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};
use EasyCorp\Bundle\EasyAdminBundle\Field\{ArrayField, IdField, EmailField, TextField};


class UserCrudController extends AbstractCrudController
{
    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher
    ) {}

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
            ->setEntityPermission('ROLE_ADMIN');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
                ->hideOnForm();

        yield FormField::addPanel('Identité');
        yield TextField::new('first_name', 'Prénom')
                ->setColumns(6);
        yield TextField::new('last_name', 'Nom')
                ->setColumns(6);
        yield ChoiceField::new('gender', 'Genre')
                ->setChoices([
                    'Femme' => 'femme',
                    'Homme' => 'homme',
                    'Autre' => 'autre',
                ]);
                
        //TODO : Find a way to create a custom field fetching geoapi like in front
        // yield ArrayField::new('position', 'Position')
        //         ->onlyOnForms();

        yield FormField::addPanel('Sécurité');
        yield EmailField::new('email', 'Email');
        yield TextField::new('password', 'Mot de passe')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Confirmation Password'],
                    'mapped' => false,
                ])
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->onlyOnForms();
        yield ChoiceField::new('roles', 'Role')
                ->setChoices([               
                    'Administrateur'    => "ROLE_ADMIN",
                    'Editeur'           => "ROLE_EDITOR",
                    'Utilisateur'       => "ROLE_USER"
                ])
                ->allowMultipleChoices();
                                                
        yield FormField::addPanel('Conducteur');
        yield BooleanField::new('driver', 'Conducteur')
                ->renderAsSwitch(true)
                ->onlyOnForms();

        yield TextField::new('car_type', 'Modèle')
                ->onlyOnForms()
                ->setColumns(4);

        yield TextField::new('car_registration', 'Immatriculation')
                ->onlyOnForms()
                ->setColumns(4);

        yield IntegerField::new('car_nb_places', 'Nombre de places')
                ->onlyOnForms()
                ->setColumns(4);

        yield FormField::addPanel('Données');
        yield AssociationField::new('travels', 'Voyages')->setCrudController(TravelCrudController::class)
                ->onlyOnForms()
                ->setColumns(6)
                ->setFormTypeOption('disabled', 'disabled');
        yield AssociationField::new('inscriptions', 'Inscriptions')->setCrudController(TravelCrudController::class)
                ->onlyOnForms()
                ->setColumns(6)
                ->setFormTypeOption('disabled', 'disabled');
        yield AssociationField::new('conversations', 'Conversations')
                ->onlyOnForms()
                ->hideOnForm()
                ->setColumns(6)
                ->setFormTypeOption('disabled', 'disabled');
        yield AssociationField::new('messages', 'Messages')
                ->hideOnForm()
                ->setColumns(6)
                ->setFormTypeOption('disabled', 'disabled');
        yield AssociationField::new('reports', 'Rapports')
                ->onlyOnForms()
                ->hideOnForm()
                ->setColumns(6)
                ->setFormTypeOption('disabled', 'disabled');

    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword() {
        return function($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->userPasswordHasher->hashPassword($this->getUser(), $password);
            $form->getData()->setPassword($hash);
        };
    }

}
