<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseShemaValidationTest extends WebTestCase
{
    private $pdo;
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private SchemaTool $schemaTool;

    protected function setUp(): void
    {   
         self::bootKernel();
        
        $container = static::getContainer();
        $this->schemaTool = new SchemaTool($container->get('doctrine')->getManager());
        $dsn = 'mysql:host=127.0.0.1;port=3306;charset=utf8mb4';
        $user = 'root';
        $password = '';
        $dbName = 'testarcadia_db_test';

        // Création de la base de données avant de créer la connexion Doctrine
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->exec("DROP DATABASE IF EXISTS $dbName");
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName");
        $this->pdo->exec("USE $dbName");

        $this->entityManager = $container->get('doctrine')->getManager();
        $this->schemaTool = new SchemaTool($this->entityManager);

        // charger le fichier SQL pour créer la base de données
        $sql = file_get_contents('create_database.sql');
        $this->pdo->exec($sql);
    }

    public function testSchemaValidation()
    {   
       // Utiliser schemaTool pour obtenir les SQL de mise à jour du schéma
        $command = $this->schemaTool->getUpdateSchemaSql($this->entityManager->getMetadataFactory()->getAllMetadata());
        
        // Vérifier que le schéma est bien synchronisé
        $this->assertEmpty($command, 'The database schema is not in sync with the current mapping file.');
    }

}