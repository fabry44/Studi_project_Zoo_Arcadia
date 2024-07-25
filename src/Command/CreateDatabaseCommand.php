<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use PDO;

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
            ->setHelp('Cette commande permet de créer une base de données à partir d\'un fichier SQL "create_database.sql"...')
            ->addArgument('db_type', InputArgument::REQUIRED, 'Le type de base de données (mariadb ou postgresql)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dbType = $input->getArgument('db_type');
        // $database = 'arcadia_db';

        if ($dbType === 'mariadb') {
            return $this->createMariaDBDatabase($io);
        } elseif ($dbType === 'postgresql') {
            return $this->createPostgreSQLDatabase($io);
        } else {
            $io->error('Type de base de données non supporté. Utilisez "mariadb" ou "postgresql".');
            return Command::FAILURE;
        }
    }

    private function createMariaDBDatabase(SymfonyStyle $io): int
    {
        $dsn = getenv('DATABASE_URL');
        $dbOptions = parse_url($dsn);

        $host = $dbOptions['host'];
        $port = $dbOptions['port'];
        $user = $dbOptions['user'];
        $password = $dbOptions['pass'];
        $database = ltrim($dbOptions['path'], '/');

        try {
            $pdo = new PDO($dsn);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS $database");
            $io->success("Base de données '$database' créée avec succès (MariaDB).");

            // Lire et exécuter le fichier SQL pour MariaDB
            $sql = file_get_contents('create_database_mariadb.sql');
            if (!$sql) {
                $io->error('Le fichier SQL pour MariaDB est vide.');
                return Command::FAILURE;
            }

            $pdo->exec("USE $database");
            $pdo->exec($sql);
            $io->success('Base de données créée avec succès (MariaDB).');
            return Command::SUCCESS;
        } catch (\PDOException $e) {
            $io->error('Impossible de créer la base de données MariaDB: ' . $e->getMessage());
            return Command::FAILURE;
        }

        // Importation des données pour MariadB

        $importData = file_get_contents('import_data_mariadb.sql');
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
    }

    private function createPostgreSQLDatabase(SymfonyStyle $io, string $database): int
    {
        $dsn = getenv('DATABASE_URL');
        $dbOptions = parse_url($dsn);

        $host = $dbOptions['host'];
        $port = $dbOptions['port'];
        $user = $dbOptions['user'];
        $password = $dbOptions['pass'];
        $database = ltrim($dbOptions['path'], '/');


        try {
            $io->writeln('Connexion à PostgreSQL...');
            $pdo = new PDO("pgsql:host=$host;port=$port;database=postgres", $user, $password);
            $io->writeln('Connexion réussie.');

            // Vérifier si la base de données existe
            $io->writeln('Vérification de l\'existence de la base de données...');
            $result = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '$database'");
            if (!$result->fetch()) {
                // Créer la base de données si elle n'existe pas
                $io->writeln('Création de la base de données...');
                $pdo->exec("CREATE DATABASE $database");
                $io->success("Base de données '$database' créée avec succès (PostgreSQL).");
            } else {
                $io->success("Base de données '$database' déjà existante (PostgreSQL).");
            }

            // Connexion à la base de données nouvellement créée
            $io->writeln('Connexion à la nouvelle base de données...');
            $pdo = new PDO("pgsql:host=$host;port=$port;database=$database", $user, $password);
            $io->writeln('Connexion réussie à la nouvelle base de données.');

            // Lire et exécuter le fichier create_database_pgsql.sql pour PostgreSQL
            $io->writeln('Lecture du fichier create_database_pgsql.sql');
            $sql = file_get_contents('create_database_pgsql.sql');
            if (!$sql) {
                $io->error('Le fichier create_database_pgsql.sql pour PostgreSQL est vide.');
                return Command::FAILURE;
            }

            $io->writeln('Exécution du script create_database_pgsql.sql');
            $pdo->exec($sql);
            $io->success('Tables créées avec succès (PostgreSQL).');

            // Lire et exécuter le fichier import_data_pgsql.sql pour PostgreSQL
            $io->writeln('Lecture du fichier import_data_pgsql.sql');
            $importData = file_get_contents('import_data_pgsql.sql');
            if (!$importData) {
                $io->error('Le fichier import_data_pgsql.sql pour PostgreSQL est vide.');
                return Command::FAILURE;
            }

            $io->writeln('Exécution du script import_data_pgsql.sql');
            $pdo->exec($importData);
            $io->success('Données insérées avec succès (PostgreSQL).');

            return Command::SUCCESS;
        } catch (\PDOException $e) {
            $io->error('Impossible de créer la base de données PostgreSQL: ' . $e->getMessage());
            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error('Une erreur inattendue s\'est produite: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
