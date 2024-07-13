<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use App\Controller\Admin\Crud\UtilisateursCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Bundle\SecurityBundle\Security;

class UtilisateursVoter extends Voter
{
    private $security;
    private $adminContextProvider;

    public function __construct(Security $security, AdminContextProvider $adminContextProvider)
    {
        $this->security = $security;
        $this->adminContextProvider = $adminContextProvider;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [Permission::EA_EXECUTE_ACTION]);
    }
    
    /**
     * Cette méthode vérifie si l'utilisateur a le droit d'effectuer une action dans le UtilisateursCrudController.
     *
     * @param string $attribute L'attribut à vérifier.
     * @param mixed $subject Le sujet sur lequel l'attribut est vérifié.
     * @param TokenInterface $token Le jeton d'authentification de l'utilisateur.
     * @return bool Retourne true si l'utilisateur a le droit à l'action dans le UtilisateursCrudController, sinon false.
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $userId = $user->getId();

        if (!$user) {
            return false;
        }

        if ($userId == null) {
            return false;
        }

        // Vérifier si l'utilisateur a le rôle d'administrateur, d'employé ou de vétérinaire
        if (!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('ROLE_EMPLOYE') && !$this->security->isGranted('ROLE_VETERINAIRE')){
            return false;
        }

        $context = $this->adminContextProvider->getContext();

        $crudController = $context->getCrud()->getControllerFqcn();

        // Vérifier si le contrôleur CRUD est celui de l'entité Utilisateurs
        if ($crudController !== UtilisateursCrudController::class) {
            return true;
        }

        if ($crudController == UtilisateursCrudController::class) {
            if ($attribute === Permission::EA_EXECUTE_ACTION) {
                return $this->canExecuteAction($context, $userId);
            }
            return true;
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur peut exécuter une action spécifique c'est a dire INDEX, DETAIL, NEW, EDIT ou DELETE.
     *
     * @param $context L'objet de contexte contenant la requête.
     * @param $userId L'identifiant de l'utilisateur.
     * @return bool Retourne true si l'utilisateur peut exécuter l'action, sinon false.
     */
    private function canExecuteAction($context, $userId): bool
    {
        $action = $context->getRequest()->query->get('crudAction');
        
        // Vérifier si l'action est autorisée pour l'utilisateur
        if (in_array($action, ['index', 'new', 'delete'])) {
            return $this->security->isGranted('ROLE_ADMIN');
        }
        // Vérifier si l'action est autorisée pour l'utilisateur en comparant l'identifiant de l'utilisateur avec l'identifiant de l'entité en cours
        if ($action === 'detail' || $action === 'edit') {
            $contextId = $context->getRequest()->query->get('entityId');
            if ($contextId && $userId && null !== $userId) {
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                return $userId == $contextId;
            }
            return false;
        }

        return false;
    }
}
