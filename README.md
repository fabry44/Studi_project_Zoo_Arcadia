# Studi_project_Zoo_Arcadia
Project d'etude Studi (ECF) : conception d'un site web Zoo-Arcadia


## Création de la base de données

Pour créer la base de données nécessaire à l'application, exécutez la commande suivante :


# Studi_project_Zoo_Arcadia
Project d'etude Studi (ECF) : conception d'un site web Zoo-Arcadia


## Création de la base de données

Pour créer la base de données nécessaire à l'application, exécutez les commandes suivantes :

```bash
php bin/console app:create-database
```

```bash
php bin/console app:create-admin
```
La deuxième commande permet de créer un administrateur via la console (la seule possibilité).

## Login
Voici trois comptes en exemples :

1. Compte administrateur :
  - Email : admin@gmail.com
  - Nom : L'administrateur
  - Prénom : UserAdmin
  - Mot de passe : 12345678
  - Rôles : ["ROLE_ADMIN"]
  - Vérifié : Oui

2. Compte vétérinaire :
  - Email : vétérinaire@gmail.com
  - Nom : Le vétérinaire
  - Prénom : UserVétérinaire
  - Mot de passe : 12345678
  - Rôles : ["ROLE_VETERINAIRE"]
  - Vérifié : Oui

3. Compte employé :
  - Email : employé@gmail.com
  - Nom : L'employé
  - Prénom : UserEmployé
  - Mot de passe : 12345678
  - Rôles : ["ROLE_EMPLOYE"]
  - Vérifié : Oui

