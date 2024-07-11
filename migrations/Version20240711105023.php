<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240711105023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alimentations ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE alimentations ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN alimentations.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE animaux ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE avis ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE avis_habitats ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE avis_habitats ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN avis_habitats.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE habitats ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE img_animaux ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE img_habitats ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE races ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE rapports_veterinaires ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE rapports_veterinaires ALTER date TYPE DATE');
        $this->addSql('COMMENT ON COLUMN rapports_veterinaires.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE services ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE utilisateurs ALTER id DROP DEFAULT');
        $this->addSql('ALTER INDEX utilisateurs_username_key RENAME TO UNIQ_IDENTIFIER_USERNAME');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE img_animaux_id_seq');
        $this->addSql('SELECT setval(\'img_animaux_id_seq\', (SELECT MAX(id) FROM img_animaux))');
        $this->addSql('ALTER TABLE img_animaux ALTER id SET DEFAULT nextval(\'img_animaux_id_seq\')');
        $this->addSql('CREATE SEQUENCE services_id_seq');
        $this->addSql('SELECT setval(\'services_id_seq\', (SELECT MAX(id) FROM services))');
        $this->addSql('ALTER TABLE services ALTER id SET DEFAULT nextval(\'services_id_seq\')');
        $this->addSql('CREATE SEQUENCE avis_habitats_id_seq');
        $this->addSql('SELECT setval(\'avis_habitats_id_seq\', (SELECT MAX(id) FROM avis_habitats))');
        $this->addSql('ALTER TABLE avis_habitats ALTER id SET DEFAULT nextval(\'avis_habitats_id_seq\')');
        $this->addSql('ALTER TABLE avis_habitats ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN avis_habitats.date IS NULL');
        $this->addSql('CREATE SEQUENCE avis_id_seq');
        $this->addSql('SELECT setval(\'avis_id_seq\', (SELECT MAX(id) FROM avis))');
        $this->addSql('ALTER TABLE avis ALTER id SET DEFAULT nextval(\'avis_id_seq\')');
        $this->addSql('CREATE SEQUENCE utilisateurs_id_seq');
        $this->addSql('SELECT setval(\'utilisateurs_id_seq\', (SELECT MAX(id) FROM utilisateurs))');
        $this->addSql('ALTER TABLE utilisateurs ALTER id SET DEFAULT nextval(\'utilisateurs_id_seq\')');
        $this->addSql('ALTER INDEX uniq_identifier_username RENAME TO utilisateurs_username_key');
        $this->addSql('CREATE SEQUENCE img_habitats_id_seq');
        $this->addSql('SELECT setval(\'img_habitats_id_seq\', (SELECT MAX(id) FROM img_habitats))');
        $this->addSql('ALTER TABLE img_habitats ALTER id SET DEFAULT nextval(\'img_habitats_id_seq\')');
        $this->addSql('CREATE SEQUENCE habitats_id_seq');
        $this->addSql('SELECT setval(\'habitats_id_seq\', (SELECT MAX(id) FROM habitats))');
        $this->addSql('ALTER TABLE habitats ALTER id SET DEFAULT nextval(\'habitats_id_seq\')');
        $this->addSql('CREATE SEQUENCE animaux_id_seq');
        $this->addSql('SELECT setval(\'animaux_id_seq\', (SELECT MAX(id) FROM animaux))');
        $this->addSql('ALTER TABLE animaux ALTER id SET DEFAULT nextval(\'animaux_id_seq\')');
        $this->addSql('CREATE SEQUENCE races_id_seq');
        $this->addSql('SELECT setval(\'races_id_seq\', (SELECT MAX(id) FROM races))');
        $this->addSql('ALTER TABLE races ALTER id SET DEFAULT nextval(\'races_id_seq\')');
        $this->addSql('CREATE SEQUENCE alimentations_id_seq');
        $this->addSql('SELECT setval(\'alimentations_id_seq\', (SELECT MAX(id) FROM alimentations))');
        $this->addSql('ALTER TABLE alimentations ALTER id SET DEFAULT nextval(\'alimentations_id_seq\')');
        $this->addSql('ALTER TABLE alimentations ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN alimentations.date IS NULL');
        $this->addSql('CREATE SEQUENCE rapports_veterinaires_id_seq');
        $this->addSql('SELECT setval(\'rapports_veterinaires_id_seq\', (SELECT MAX(id) FROM rapports_veterinaires))');
        $this->addSql('ALTER TABLE rapports_veterinaires ALTER id SET DEFAULT nextval(\'rapports_veterinaires_id_seq\')');
        $this->addSql('ALTER TABLE rapports_veterinaires ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN rapports_veterinaires.date IS NULL');
    }
}
