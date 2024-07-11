<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240711105421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alimentations ALTER date TYPE DATE');
        $this->addSql('COMMENT ON COLUMN alimentations.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE avis_habitats ALTER date TYPE DATE');
        $this->addSql('COMMENT ON COLUMN avis_habitats.date IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE avis_habitats ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN avis_habitats.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE alimentations ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN alimentations.date IS \'(DC2Type:datetime_immutable)\'');
    }
}
