<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626113937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alimentations (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, employe_id INT NOT NULL, date_alimentation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', nourriture VARCHAR(255) NOT NULL, quantite DOUBLE PRECISION NOT NULL, INDEX IDX_CD56093C8E962C16 (animal_id), INDEX IDX_CD56093C1B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animaux (id INT AUTO_INCREMENT NOT NULL, habitat_id INT NOT NULL, race_id INT NOT NULL, prenom VARCHAR(255) NOT NULL, INDEX IDX_9ABE194DAFFE2D26 (habitat_id), INDEX IDX_9ABE194D6E59D40D (race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, pseudo VARCHAR(255) NOT NULL, avis LONGTEXT NOT NULL, valide TINYINT(1) NOT NULL, INDEX IDX_8F91ABF01B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis_habitats (id INT AUTO_INCREMENT NOT NULL, habitat_id INT DEFAULT NULL, veterinaire_id INT DEFAULT NULL, avis LONGTEXT NOT NULL, date_avis DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_5CAC3456AFFE2D26 (habitat_id), INDEX IDX_5CAC34565C80924 (veterinaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habitats (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, descript LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE img_animaux (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, image LONGBLOB NOT NULL, INDEX IDX_D7D15DA58E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE img_habitats (id INT AUTO_INCREMENT NOT NULL, habitat_id INT NOT NULL, image LONGBLOB NOT NULL, INDEX IDX_1B7897FDAFFE2D26 (habitat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE races (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rapports_veterinaires (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, veterinaire_id INT NOT NULL, etat VARCHAR(255) NOT NULL, nourriture VARCHAR(255) NOT NULL, grammage DOUBLE PRECISION NOT NULL, detail LONGTEXT DEFAULT NULL, INDEX IDX_A35921A8E962C16 (animal_id), INDEX IDX_A35921A5C80924 (veterinaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, descript LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alimentations ADD CONSTRAINT FK_CD56093C8E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alimentations ADD CONSTRAINT FK_CD56093C1B65292 FOREIGN KEY (employe_id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animaux ADD CONSTRAINT FK_9ABE194DAFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitats (id)');
        $this->addSql('ALTER TABLE animaux ADD CONSTRAINT FK_9ABE194D6E59D40D FOREIGN KEY (race_id) REFERENCES races (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF01B65292 FOREIGN KEY (employe_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE avis_habitats ADD CONSTRAINT FK_5CAC3456AFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitats (id)');
        $this->addSql('ALTER TABLE avis_habitats ADD CONSTRAINT FK_5CAC34565C80924 FOREIGN KEY (veterinaire_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE img_animaux ADD CONSTRAINT FK_D7D15DA58E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id)');
        $this->addSql('ALTER TABLE img_habitats ADD CONSTRAINT FK_1B7897FDAFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitats (id)');
        $this->addSql('ALTER TABLE rapports_veterinaires ADD CONSTRAINT FK_A35921A8E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id)');
        $this->addSql('ALTER TABLE rapports_veterinaires ADD CONSTRAINT FK_A35921A5C80924 FOREIGN KEY (veterinaire_id) REFERENCES utilisateurs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alimentations DROP FOREIGN KEY FK_CD56093C8E962C16');
        $this->addSql('ALTER TABLE alimentations DROP FOREIGN KEY FK_CD56093C1B65292');
        $this->addSql('ALTER TABLE animaux DROP FOREIGN KEY FK_9ABE194DAFFE2D26');
        $this->addSql('ALTER TABLE animaux DROP FOREIGN KEY FK_9ABE194D6E59D40D');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF01B65292');
        $this->addSql('ALTER TABLE avis_habitats DROP FOREIGN KEY FK_5CAC3456AFFE2D26');
        $this->addSql('ALTER TABLE avis_habitats DROP FOREIGN KEY FK_5CAC34565C80924');
        $this->addSql('ALTER TABLE img_animaux DROP FOREIGN KEY FK_D7D15DA58E962C16');
        $this->addSql('ALTER TABLE img_habitats DROP FOREIGN KEY FK_1B7897FDAFFE2D26');
        $this->addSql('ALTER TABLE rapports_veterinaires DROP FOREIGN KEY FK_A35921A8E962C16');
        $this->addSql('ALTER TABLE rapports_veterinaires DROP FOREIGN KEY FK_A35921A5C80924');
        $this->addSql('DROP TABLE alimentations');
        $this->addSql('DROP TABLE animaux');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE avis_habitats');
        $this->addSql('DROP TABLE habitats');
        $this->addSql('DROP TABLE img_animaux');
        $this->addSql('DROP TABLE img_habitats');
        $this->addSql('DROP TABLE races');
        $this->addSql('DROP TABLE rapports_veterinaires');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE utilisateurs');
    }
}
