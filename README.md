# Studi_project_Zoo_Arcadia
Projet d'étude Studi (ECF) : conception d'un site web Zoo-Arcadia

## Prérequis

- PHP 8.x
- Composer
- Symfony CLI
- MariaDB ou MySQL
- PostgreSQL
- MongoDB
- Node.js et npm (pour la gestion des assets)

## Installation de l'application

Clonez le dépôt et installez les dépendances :

```bash
git clone <url_du_depot>
cd Studi_project_Zoo_Arcadia
composer install
```

Configuration de l'environnement
Copiez le fichier .env et configurez-le selon vos besoins : .env .env.local

## Création de la base de données relationnelle


Création de la base de données relationnelle
Pour créer la base de données nécessaire à l'application, exécutez les commandes suivantes :

Pour une base de données MariaDB/MySQL :

```bash
php bin/console doctrine:database:create mariadb
```

Pour une base de données PostgreSQL :

```bash
php bin/console doctrine:database:create postgresql  
```

Vérifiez si la base de données créée est bien synchronisée avec les entités Doctrine :

```bash
php bin/console doctrine:schema:validate
```

Si le schéma de la base de données n'est pas synchronisé, effectuez les commandes suivantes :

```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

Puis, testez à nouveau avec :

```bash
php bin/console doctrine:schema:validate
```
Si le schéma n'est toujours pas synchronisé, utilisez :

```bash
php bin/console doctrine:schema:update --force
```
Testez une dernière fois :

```bash
php bin/console doctrine:schema:validate
```

## Création de la base de données MongoDB

Démarrez votre instance MongoDB et créez les collections nécessaires :

```bash
php bin/console doctrine:mongodb:schema:create
```

Création du compte administrateur
Créez un compte administrateur via la console :

```bash
php bin/console app:create-admin
```

Comptes d'exemple
Voici trois comptes en exemples :

## Compte administrateur :

Email : admin@gmail.com
Nom : L'administrateur
Prénom : UserAdmin
Mot de passe : 12345678
Rôles : ["ROLE_ADMIN"]

## Compte vétérinaire :

Email : veterinaire@gmail.com
Nom : Le vétérinaire
Prénom : UserVétérinaire
Mot de passe : 12345678
Rôles : ["ROLE_VETERINAIRE"]

## Compte employé :

Email : employe@gmail.com
Nom : L'employé
Prénom : UserEmployé
Mot de passe : 12345678
Rôles : ["ROLE_EMPLOYE"]




## Lancer l'application
Démarrez le serveur Symfony :
émarrez le serveur Symfony :

```bash
symfony server:start   
``