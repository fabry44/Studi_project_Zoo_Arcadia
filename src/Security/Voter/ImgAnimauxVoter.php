<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use App\Controller\Admin\Crud\ImgAnimauxCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Bundle\SecurityBundle\Security;

class ImgAnimauxVoter extends Voter
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
     * Cette méthode vérifie si l'utilisateur a le droit d'effectuer une action dans le ImgAnimauxCrudController.
     *
     * @param string $attribute L'attribut à vérifier.
     * @param mixed $subject Le sujet sur lequel l'attribut est vérifié.
     * @param TokenInterface $token Le jeton d'authentification de l'utilisateur.
     * @return bool Retourne true si l'utilisateur a le droit à l'action dans le ImgAnimauxCrudController, sinon false.
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

        // Vérifier si le contrôleur CRUD est bien conerné
        if ($crudController !== ImgAnimauxCrudController::class) {
            return true;
        }

        if ($crudController == ImgAnimauxCrudController::class) {
            if ($attribute === Permission::EA_EXECUTE_ACTION) {
                return $this->canExecuteAction($context);
            }
            return true;
        }

        return false;
    }

    /**
     * Filte l'accès aux action de modification, suppression et création d'image uniquement pour l'administrateurs.
     *
     * @param $context L'objet de contexte contenant la requête.
     * @param $userId L'identifiant de l'utilisateur.
     * @return bool Retourne true si l'utilisateur peut exécuter l'action, sinon false.
     */
    private function canExecuteAction($context): bool
  {
      $action = $context->getRequest()->query->get('crudAction');
      
      // Les actions 'new', 'edit' et 'delete' sont réservées aux administrateurs
      if (in_array($action, ['new', 'edit', 'delete'])) {
          return $this->security->isGranted('ROLE_ADMIN');
      }
      
      // Les actions 'index' et 'detail' sont autorisées pour tous les rôles
      if (in_array($action, ['index', 'detail'])) {
          return $this->security->isGranted('ROLE_EMPLOYE') || $this->security->isGranted('ROLE_VETERINAIRE') || $this->security->isGranted('ROLE_ADMIN');
      }

      return false;
  }

}
