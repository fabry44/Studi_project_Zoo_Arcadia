<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Utilisateurs;
use App\Repository\UtilisateursRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Security\EmailVerifier;


class UtilisateursCrudController extends AbstractCrudController
{   
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public static function getEntityFqcn(): string
    {
        return Utilisateurs::class;
    }

    
    public function configureFields(string $pageName): iterable
    {   $roles = [
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
            EmailField::new('username'),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('password')
                ->onlyOnForms()
                ->setFormTypeOptions([
                    'required' => true,
                    'mapped' => true,
                ]),
            // TextField::new('passwordConfirmation')
            //     ->onlyOnForms()
            //     ->setFormTypeOptions([
            //         'mapped' => false,
            //         'required' => true,
            //     ]),
            ChoiceField::new('roles')
                ->setChoices([
                    'Employé' => 'ROLE_EMPLOYE',
                    'Vétérinaire' => 'ROLE_VETERINAIRE',
                ])
                ->allowMultipleChoices(true)
                ->renderExpanded(true)
                ->onlyOnForms(),
            ArrayField::new('roles')
                ->formatValue(function ($value) use ($roles, $rolesBadges) {
                    $badges = array_map(function ($role) use ($roles, $rolesBadges) {
                        $roleName = array_search($role, $roles);
                        $badgeType = $rolesBadges[$role] ?? 'secondary';
                        return sprintf('<span class="badge badge-%s">%s</span>', $badgeType, $roleName);
                    }, $value);

                    return implode(' ', $badges);
                })
                ->hideOnForm(),
            BooleanField::new('isVerified')
                ->renderAsSwitch(false)
                ->hideOnForm(),
        ];

        // Ajoutez cette logique pour adapter le champ selon le contexte
       
        $user = $this->getContext()->getEntity()->getInstance();
            // dump($user->getRoles());
        if ($pageName === Crud::PAGE_EDIT) {
            $fields[] = BooleanField::new('isVerified')                
                ->setFormTypeOption('disabled', true);
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                $fields[] = ChoiceField::new('roles')
                ->setChoices([
                    'Employé' => 'ROLE_EMPLOYE',
                    'Vétérinaire' => 'ROLE_VETERINAIRE',
                ])
                ->allowMultipleChoices(true)
                ->renderExpanded(true)
                ->onlyOnForms()
                ->setFormTypeOption('disabled', true);
            }
        }

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {   
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
            })
            ->addCssClass('btn btn-success'))
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-user-plus')->addCssClass('btn btn-success');
            })
            // Supprimer l'action de suppression sur la page INDEX
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            // Supprimer l'action de suppression sur la page DETAIL
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            // Configurer l'action de suppression sur la page d'édition avec conditions  !'ROLE_ADMIN' == $user->getRoles()
            ->add(Crud::PAGE_EDIT, Action::DELETE)
            ->update(Crud::PAGE_EDIT, Action::DELETE, function (Action $action) {
                return $action->displayIf(function () {
                    $user = $this->getContext()->getEntity()->getInstance();
                    return $user instanceof Utilisateurs && !in_array('ROLE_ADMIN', $user->getRoles());
                });
            });
    }

    public function verifyEmail(int $id, UtilisateursRepository $utilisateursRepository): Response
    {
        
        $user = $utilisateursRepository->find($id);
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('admin_dashboard');
        }

        // Logique pour vérifier l'utilisateur
        $email = (new TemplatedEmail())
            ->from(new Address('contact@zoo-arcadia.com', 'Support Zoo Arcadia'))
            ->to($user->getUsername())
            ->subject('Merci de confirmer votre Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $email);

        $this->addFlash('success', 'Un nouvel email de vérification a été renvoyé.');
        return $this->redirectToRoute('admin_dashboard');
    }

    
}
