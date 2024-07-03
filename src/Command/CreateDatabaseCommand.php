<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Cette commande permet de créer la base de données en exécutant le script SQL "create_database.sql".
 * Utilisation de SymfonyStyle pour améliorer l'interaction avec l'utilisateur.
 */
#[AsCommand(
    name: 'app:create-database',
    description: 'Exécution du fichier SQL pour créer la base de données.',
)]
class CreateDatabaseCommand extends Command
{
    private $entityManager;

    /**
     * Constructeur pour initialiser l'EntityManager.
     *
     * @param EntityManagerInterface $entityManager Gère les entités de Doctrine.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * Configuration supplémentaire de la commande.
     */
    protected function configure(): void
    {
        $this
            ->setHelp('Cette commande permet de créer une base de données à partir d\'un fichier SQL "create_database.sql"...');
            
    }

    /**
     * Exécution de la commande qui lit et exécute le script SQL.
     *
     * @param InputInterface $input  L'interface d'entrée pour les commandes.
     * @param OutputInterface $output L'interface de sortie pour les commandes.
     * @return int Le statut de sortie de la commande (succès ou échec).
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dsn = 'mysql:host=127.0.0.1;port=3306;charset=utf8';
        $user = 'root';
        $password = '';

        // Déterminer le nom de la base de données
        $dbName = 'arcadia_db';

        try {
            $pdo = new \PDO($dsn, $user, $password);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName");
            $pdo->exec("USE $dbName");
            $io->success("Base de données '$dbName' créée ou déjà existante.");
        } catch (\PDOException $e) {
            $io->error('Impossible de créer la base de données: ' . $e->getMessage());
            return Command::FAILURE;
        }

        // Exécution du reste du script SQL
        $sql = file_get_contents('create_database.sql');
        if (!$sql) {
            $io->error('Le fichier SQL est vide.');
            return Command::FAILURE;
        }
        
        // Utilisation de $pdo pour exécuter le script SQL

        // Installation de la base de données
        try {
            $stmt = $pdo->prepare($sql);  
            $stmt->execute();
            $io->success('Base de données créée avec succès.');
        } catch (\PDOException $e) {
            $io->error('Échec de l\'exécution du script SQL: ' . $e->getMessage());
            return Command::FAILURE;
        }


        // Importation des données

        $importData = file_get_contents('import_data.sql');
        if (!$importData) {
            $io->error('Le fichier SQL est vide.');
            return Command::FAILURE;
        }
        
        try {
            $stmt = $pdo->prepare($importData);  
            $stmt->execute();
            $io->success('les donnée ont été importées avec succès.');
        } catch (\PDOException $e) {
            $io->error('Échec de l\'exécution du script SQL: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
