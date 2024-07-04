# Studi_project_Zoo_Arcadia
Project d'etude Studi (ECF) : conception d'un site web Zoo-Arcadia


## Création de la base de données

Pour créer la base de données nécessaire à l'application, exécutez les commandes suivantes :

Pour une base de donnée MariadB MySQL

```bash
php bin/console app:create-database mariadb
```

Pour une base de donnée PostgreSQL

```bash
php bin/console app:create-database pgsql
```

Verification si la base de donnée créer avec les commandes SQL sont bien synchronisé avec Doctrine.

```bash
php bin/console doctrine:schema:validate
```

En principe pour MariadB, la base de donnée est bien synchroniser avec les entités doctrine en tous cas le mapping, pour PostgreSQL le schema n'est pas synchroniser par contre. 

Si le schema de la base de donnée n'est pas synchroniser effectuer la commande :

```bash
php bin/console doctrine:migrations:diff   
```

puis
```bash
symfony.exe console doctrine:migrations:migrate 
```

puis tester une nouvelle fois avec 

```bash
php bin/console doctrine:schema:validate
```

Si le schemas n'est pas encore Sync, alors:

```bash
php bin/console doctrine:schema:update --dump-sql
```

puis un :

```bash
php bin/console doctrine:schema:update --force
```

refaite les test :

```bash
php bin/console doctrine:schema:validate
```

Tout est près !

### Création du compte administrateur

```bash
php bin/console app:create-admin
```
La deuxième commande permet de créer un administrateur via la console (la seule possibilité).

#### Login avec donnée préenregistrer import_data.sql
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

