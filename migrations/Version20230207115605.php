<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207115605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fichier_decor (id INT AUTO_INCREMENT NOT NULL, plan_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_87CE8798E899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fichier_decor ADD CONSTRAINT FK_87CE8798E899029B FOREIGN KEY (plan_id) REFERENCES plan (id)');
        $this->addSql('ALTER TABLE plan ADD note_decortiqueur LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier_decor DROP FOREIGN KEY FK_87CE8798E899029B');
        $this->addSql('DROP TABLE fichier_decor');
        $this->addSql('ALTER TABLE plan DROP note_decortiqueur');
    }
}
