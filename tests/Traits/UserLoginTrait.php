<?php

namespace App\tests\Traits;

use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
// use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait UserLoginTrait
{
    private function createUser(string $username, string $nom, string $prenom, array $roles, bool $isVerified, string $password, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $user = (new Utilisateurs())->setUsername($username);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setRoles($roles);
        $user->setIsVerified($isVerified);
        $user->setPassword($passwordHasher->hashPassword($user, $password));

        $em->persist($user);
        $em->flush();

        return $user;
    }

    private function initializeUsers(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        // supprimer tous les utilisateurs de la base de données avant de commencer les tests de connexion
        $userRepository = $em->getRepository(Utilisateurs::class);
        foreach ($userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();

        // Créer des utilisateurs
        $this->createUser('email.admin@example.com', 'Doe', 'John', ['ROLE_ADMIN'], true, 'password', $em, $passwordHasher);
        $this->createUser('email.veterinaire@example.com', 'dupont', 'jean', ['ROLE_VETERINAIRE'], true, 'password', $em, $passwordHasher);
        $this->createUser('email.employe@example.com', 'henry', 'bernard', ['ROLE_EMPLOYE'], false, 'password', $em, $passwordHasher);
    }

    private function loginUser(KernelBrowser $client, ContainerInterface $container, Utilisateurs $user)
    {
        $session = new Session(new MockArraySessionStorage());
        $container->set('session', $session);

        $roles = $user->getRoles();
        $token = new UsernamePasswordToken($user, 'main', $roles);

        $container->get('security.token_storage')->setToken($token);

        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    public function loginAdmin(KernelBrowser $client, ContainerInterface $container)
    {
        $em = $container->get('doctrine.orm.entity_manager');
        $adminUser = $em->getRepository(Utilisateurs::class)->findOneBy(['username' => 'email.admin@example.com']);
        $this->loginUser($client, $container, $adminUser);
    }

    public function loginVeterinaire(KernelBrowser $client, ContainerInterface $container)
    {
        $em = $container->get('doctrine.orm.entity_manager');
        $veterinaireUser = $em->getRepository(Utilisateurs::class)->findOneBy(['username' => 'email.veterinaire@example.com']);
        $this->loginUser($client, $container, $veterinaireUser);
    }

    public function loginEmploye(KernelBrowser $client, ContainerInterface $container)
    {
        $em = $container->get('doctrine.orm.entity_manager');
        $employeUser = $em->getRepository(Utilisateurs::class)->findOneBy(['username' => 'email.employe@example.com']);
        $this->loginUser($client, $container, $employeUser);
    }
}
