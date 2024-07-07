<?php

namespace App\Tests\Traits;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManagerInterface;
use PDO;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

trait DatabaseTrait
{
    private $pdo;
    // private EntityManagerInterface $entityManager;
    private SchemaTool $schemaTool;
    private string $dbName = 'testarcadia_db_test';

    protected function initializeDatabase(): void
    {
        // Récupérer le container de l'application
        $container = static::getContainer();

        // Initialisation de l'Entity Manager et du Schema Tool
        $this->entityManager = $container->get('doctrine')->getManager();
        $this->schemaTool = new SchemaTool($this->entityManager);

        // Configuration de la base de données
        $dsn = 'pgsql:host=127.0.0.1;port=5432';
        $user = 'postgres';
        $password = '6986';

        // Connexion PDO
        $this->pdo = new \PDO($dsn, $user, $password);

        // Suppression et création de la base de données
        $this->terminateConnections();
        $this->pdo->exec("DROP DATABASE IF EXISTS {$this->dbName}");
        $this->pdo->exec("CREATE DATABASE {$this->dbName}");

        // Connexion à la nouvelle base de données
        $pdoWithDb = new \PDO("$dsn;dbname={$this->dbName}", $user, $password);

        // Application du schéma à la nouvelle base de données
        $this->schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    protected function terminateConnections(): void
    {
        $dsn = 'pgsql:host=127.0.0.1;port=5432';
        $user = 'postgres';
        $password = '6986';
        $pdo = new \PDO($dsn, $user, $password);

        $pdo->exec("
            SELECT pg_terminate_backend(pg_stat_activity.pid)
            FROM pg_stat_activity
            WHERE pg_stat_activity.datname = '{$this->dbName}'
            AND pid <> pg_backend_pid();
        ");
    }

    protected function dropDatabase(): void
    {
        // Fermer l'entity manager
        if ($this->entityManager->isOpen()) {
            $this->entityManager->close();
        }

        // Déconnecter le PDO
        $this->terminateConnections();
        $this->pdo->exec("DROP DATABASE {$this->dbName}");
    }
}
