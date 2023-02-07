<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207094533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decortiqueur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, diplome VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plan DROP FOREIGN KEY FK_DD5A5B7DB8AB9504');
        $this->addSql('DROP INDEX IDX_DD5A5B7DB8AB9504 ON plan');
        $this->addSql('ALTER TABLE plan CHANGE decortiqueur_id decortiqueurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plan ADD CONSTRAINT FK_DD5A5B7DE0A4D2A9 FOREIGN KEY (decortiqueurs_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_DD5A5B7DE0A4D2A9 ON plan (decortiqueurs_id)');
        $this->addSql('ALTER TABLE user ADD decortiqueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B8AB9504 FOREIGN KEY (decortiqueur_id) REFERENCES decortiqueur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B8AB9504 ON user (decortiqueur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649B8AB9504');
        $this->addSql('DROP TABLE decortiqueur');
        $this->addSql('ALTER TABLE plan DROP FOREIGN KEY FK_DD5A5B7DE0A4D2A9');
        $this->addSql('DROP INDEX IDX_DD5A5B7DE0A4D2A9 ON plan');
        $this->addSql('ALTER TABLE plan CHANGE decortiqueurs_id decortiqueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plan ADD CONSTRAINT FK_DD5A5B7DB8AB9504 FOREIGN KEY (decortiqueur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DD5A5B7DB8AB9504 ON plan (decortiqueur_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649B8AB9504 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP decortiqueur_id');
    }
}
