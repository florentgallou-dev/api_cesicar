<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\DBAL\Types\JsonType;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\{ArrayField, IdField, EmailField, TextField};
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use Symfony\Component\Form\{FormBuilderInterface, FormEvent, FormEvents};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


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
        yield IdField::new('id')->hideOnForm();
        
        yield TextField::new('first_name', 'Prénom');
        yield TextField::new('last_name', 'Nom');

        yield ChoiceField::new('gender', 'Genre')->setChoices([
                                                        'Femme' => 'femme',
                                                        'Homme' => 'homme',
                                                        'Autre' => 'autre',
                                                    ]);
        
        yield EmailField::new('email', 'Email');

        // yield TextField::new('password', 'Mot de passe')
        //                     ->hideOnIndex();
        yield TextField::new('password', 'Mot de passe')
                            ->setFormType(RepeatedType::class)
                            ->setFormTypeOptions([
                                'type' => PasswordType::class,
                                'first_options' => ['label' => 'Password'],
                                'second_options' => ['label' => '(Repeat)'],
                                'mapped' => false,
                            ])
                            ->setRequired($pageName === Crud::PAGE_NEW)
                            ->onlyOnForms()
                            ;

        yield ChoiceField::new('roles', 'Role')->setChoices([
                                                    'Administrateur'    => "ROLE_ADMIN",
                                                    'Editeur'           => "ROLE_EDITOR",
                                                    'Utilisateur'       => "ROLE_USER"
                                                ])
                                                ->allowMultipleChoices();
                                                
        yield ArrayField::new('position', 'Position');

        yield BooleanField::new('driver', 'Conducteur')->renderAsSwitch(true);
        yield TextField::new('car_type', 'Modèle')
                            ->hideOnIndex();
        yield TextField::new('car_registration', 'Immatriculation')
                            ->hideOnIndex();
        yield IntegerField::new('car_nb_places', 'Nombre de places')
                            ->hideOnIndex();

        yield AssociationField::new('inscription');
        yield AssociationField::new('travel');
        yield AssociationField::new('conversation');
        yield AssociationField::new('message');
        yield AssociationField::new('report');

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
