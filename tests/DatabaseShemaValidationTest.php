<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Traits\DatabaseTrait;

class DatabaseShemaValidationTest extends WebTestCase
{
    use DatabaseTrait;

    protected function setUp(): void
    {
        $this->initializeDatabase();
    }

    public function testSchemaValidation()
    {
        // Utiliser schemaTool pour obtenir les SQL de mise à jour du schéma
        $command = $this->schemaTool->getUpdateSchemaSql($this->entityManager->getMetadataFactory()->getAllMetadata());

        // Vérifier que le schéma est bien synchronisé
        $this->assertEmpty($command, 'Le schéma de la base de données n\'est pas synchronisé avec le fichier de mappage actuel.');
    }

    protected function tearDown(): void
    {
        $this->dropDatabase();
    }
}
