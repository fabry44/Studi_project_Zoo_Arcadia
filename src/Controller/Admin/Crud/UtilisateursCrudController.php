<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Utilisateurs;
use App\Repository\UtilisateursRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Security\EmailVerifier;
use Psr\Log\LoggerInterface;

class UtilisateursCrudController extends AbstractCrudController
{   
    private $emailVerifier;
    private $logger;

    public function __construct(EmailVerifier $emailVerifier, LoggerInterface $logger)
    {
        $this->emailVerifier = $emailVerifier;
        $this->logger = $logger;
    }

    public static function getEntityFqcn(): string
    {
        return Utilisateurs::class;
    }

    public function configureFields(string $pageName): iterable
    {   
        // Configuration des champs
        $roles = [
            'Administrateur' => 'ROLE_ADMIN',
            'Vétérinaire' => 'ROLE_VETERINAIRE',
            'Employé' => 'ROLE_EMPLOYE',
        ];

        $rolesBadges = [
            'ROLE_ADMIN' => 'success',
            'ROLE_VETERINAIRE' => 'primary',
            'ROLE_EMPLOYE' => 'info',
        ];

        $fields = [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('username')->setLabel('Email')->setRequired(true),
            TextField::new('nom')->setLabel('Nom')->setRequired(true),
            TextField::new('prenom')->setLabel('Prénom')->setRequired(true),
            TextField::new('password')->setLabel('Mot de passe')->setRequired(true)->setFormTypeOptions([
                'required' => true,
                'mapped' => true,
            ])->onlyWhenCreating(),
            TextField::new('passwordConfirmation')->setLabel('Confirmation du mot de passe')->setFormTypeOptions([
                'required' => true,
                'mapped' => false,
            ])->onlyWhenCreating(),
            ChoiceField::new('roles')->setLabel('Rôles')->setRequired(true)->setChoices([
                'Employé' => 'ROLE_EMPLOYE',
                'Vétérinaire' => 'ROLE_VETERINAIRE',
            ])->setFormTypeOptions([
                'required' => true,
            ])->allowMultipleChoices(true)->renderExpanded(true),
            ArrayField::new('roles')->setLabel('Rôles')->formatValue(function ($value) use ($roles, $rolesBadges) {
                $badges = array_map(function ($role) use ($roles, $rolesBadges) {
                    $roleName = array_search($role, $roles);
                    $badgeType = $rolesBadges[$role] ?? 'secondary';
                    return sprintf('<span class="badge badge-%s">%s</span>', $badgeType, $roleName);
                }, $value);
                return implode(' ', $badges);
            })->onlyOnIndex(),
            BooleanField::new('isVerified')->setLabel('Email vérifié')->renderAsSwitch(false)->hideOnForm(),
        ];

        $user = $this->getContext()->getEntity()->getInstance();
        
        if ($pageName === Crud::PAGE_EDIT) {
            $fields[] = BooleanField::new('isVerified')->setLabel('Email vérifié')->setFormTypeOption('disabled', true);
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                $fields[] = ChoiceField::new('roles')->setLabel('Rôles')->setChoices([
                    'Employé' => 'ROLE_EMPLOYE',
                    'Vétérinaire' => 'ROLE_VETERINAIRE',
                ])->allowMultipleChoices(true)->renderExpanded(true)->onlyOnForms()->setFormTypeOption('disabled', true);
            }
        }

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {   
        // Configuration des actions
        $deleteAction = Action::new('delete')
            ->linkToCrudAction(Crud::PAGE_DETAIL)
            ->displayIf(function (Utilisateurs $user) {
                return !in_array('ROLE_ADMIN', $user->getRoles());
            });
        
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)     
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->add(Crud::PAGE_INDEX, Action::new('verifyEmail', 'Vérifier Email')
            ->linkToRoute('verify_email', function (Utilisateurs $user) {
                return ['id' => $user->getId()];
            })->addCssClass('btn btn-primary'))
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-user-plus')->addCssClass('btn btn-primary')->setLabel('Nouvel Utilisateur');
            })
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->add(Crud::PAGE_EDIT, Action::DELETE)
            ->update(Crud::PAGE_EDIT, Action::DELETE, function (Action $action) {
                return $action->displayIf(function () {
                    $user = $this->getContext()->getEntity()->getInstance();
                    return $user instanceof Utilisateurs && !in_array('ROLE_ADMIN', $user->getRoles());
                });
            })
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureCrud(Crud $crud): Crud
    {
        // Configuration du CRUD
        return $crud
            ->setPageTitle('index', 'Gestion des Utilisateurs')
            ->setPageTitle('new', 'Créer un nouvel Utilisateur')
            ->setPageTitle('edit', 'Modifier un Utilisateur')
            ->setPageTitle('detail', 'Détails de l\'Utilisateur');
    }

    /**
    * Envoie un email de vérification à l'utilisateur.
     *
     * @param int $id
     * @param UtilisateursRepository $utilisateursRepository
     * @return Response
     */
    public function verifyEmail(int $id, UtilisateursRepository $utilisateursRepository): Response
    {
        // Find the user by id
        $user = $utilisateursRepository->find($id);
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('admin_dashboard');
        }

        // Logic to send verification email
        $email = (new TemplatedEmail())
            ->from(new Address('contact@zoo-arcadia.com', 'Support Zoo Arcadia'))
            ->to($user->getUsername())
            ->subject('Merci de confirmer votre Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');

        $result = $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $email, $this->logger);

        if ($result) {
            $this->addFlash('success', 'Un nouvel email de vérification a été renvoyé.');
        } else {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de l\'email de vérification.');
        }

        return $this->redirectToRoute('admin_dashboard');
    }
}
