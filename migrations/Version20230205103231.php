<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205103231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plan ADD decortiqueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plan ADD CONSTRAINT FK_DD5A5B7DB8AB9504 FOREIGN KEY (decortiqueur_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_DD5A5B7DB8AB9504 ON plan (decortiqueur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plan DROP FOREIGN KEY FK_DD5A5B7DB8AB9504');
        $this->addSql('DROP INDEX IDX_DD5A5B7DB8AB9504 ON plan');
        $this->addSql('ALTER TABLE plan DROP decortiqueur_id');
    }
}
